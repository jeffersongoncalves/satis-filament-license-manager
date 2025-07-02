<?php

namespace App\Jobs;

use App\Data\PackageData;
use App\Data\RepositoryData;
use App\Data\SatisConfig;
use App\Enums\PackageType;
use App\Models\Package;
use App\Models\Token;
use Illuminate\Contracts\Process\ProcessResult;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Process\Exceptions\ProcessTimedOutException;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Process;
use RuntimeException;

class SyncTokenPackages implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    public $timeout = 60 * 60 * 24;

    /**
     * Create a new job instance.
     */
    public function __construct(protected Token $token)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(Filesystem $filesystem): void
    {
        if (is_dir(storage_path("app/private/satis/{$this->token->id}"))) {
            $filesystem->deleteDirectory(storage_path("app/private/satis/{$this->token->id}"));
        }

        $config = SatisConfig::make();
        $config->homepage(config('app.url'));
        $config->notifyBatch(config('app.url').'/api/composer/downloads');
        $config->outputDir(storage_path("app/private/satis/{$this->token->id}/"));
        $this->token
            ->packages()
            ->orderBy('name')
            ->get()
            ->each(function (Package $package) use ($config) {
                $config->repository(
                    new RepositoryData(
                        name: $package->name,
                        type: $this->getRepositoryType($package),
                        url: $this->getRepositoryUrl($package),
                        options: $this->getRepositoryOptions($package)
                    )
                );

                $config->require(
                    new PackageData(name: $package->name)
                );
            });

        $config->merge(
            SatisConfig::load(base_path('satis.json'))
        );

        $config->saveAs(
            storage_path("app/private/satis/{$this->token->id}/satis.json")
        );

        $composer_path = storage_path('app/private/composer');
        rescue(fn () => mkdir(dirname($composer_path), 0755, true), report: false);
        try {
            tap(
                Process::timeout(60 * 60 * 24)
                    ->env(['COMPOSER_HOME' => $composer_path])
                    ->run("php vendor/bin/satis build {$config->path} --skip-errors"),
                function (ProcessResult $process) {
                    if ($process->successful()) {
                        $this->token->packages()->get()->each(function (Package $package) {
                            ProcessPackageDependency::dispatch($package);
                        });
                    }
                }
            );
        } catch (RuntimeException|ProcessTimedOutException) {
        }

        $config->delete();
    }

    private function getRepositoryType(Package $package): string
    {
        return match ($package->type) {
            PackageType::Composer => 'composer',
            PackageType::Github => 'vcs',
        };
    }

    private function getRepositoryUrl(Package $package): string
    {
        return match ($package->type) {
            PackageType::Github => str($package->url)
                ->prepend('https://')
                ->replaceFirst('git@', "{$package->username}:{$package->password}@")
                ->replaceLast(':', '/')
                ->toString(),
            default => $package->url
        };
    }

    private function getRepositoryOptions(Package $package): array
    {
        return match ($package->type) {
            PackageType::Composer => [
                'http' => [
                    'header' => [
                        'Authorization: Basic '.base64_encode("{$package->username}:{$package->password}"),
                    ],
                ],
            ],
            default => [],
        };
    }
}
