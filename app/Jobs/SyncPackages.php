<?php

namespace App\Jobs;

use App\Data\Package as PackageData;
use App\Data\Repository as RepositoryData;
use App\Data\SatisConfig;
use App\Enums\PackageType;
use App\Models\Package;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SyncPackages implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $config = SatisConfig::make();
        $config->homepage(config('app.url'));
        $config->outputDir(storage_path('app/private/satis/'));
        Package::query()
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
            storage_path('app/private/satis/satis.json')
        );

        Package::query()
            ->select(['url', 'type'])
            ->groupBy(['url', 'type'])
            ->get()
            ->each(function (Package $package) use ($config) {
                ProcessSatisByPathAndRepositoryUrl::dispatch($config->path, $this->getRepositoryUrl($package));
            });
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
