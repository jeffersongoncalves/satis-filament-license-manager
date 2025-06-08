<?php

namespace App\Jobs;

use App\Models\Dependency;
use App\Models\Package;
use App\Models\PackageRelease;
use App\Models\PackageReleaseRequire;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\File;

class ProcessPackageDependency implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(protected readonly Package $package)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if (! File::exists($filename = storage_path('app/private/composer/cache/repo/'.$this->package->folder.'/provider-'.$this->package->name_provider.'.json'))) {
            return;
        }
        $file = File::json($filename);
        $releases = $file['packages'][$this->package->name];
        foreach ($releases as $release) {
            $packageRelease = PackageRelease::firstOrCreate(
                [
                    'package_id' => $this->package->id,
                    'version' => $release['version'],
                ],
                [
                    'time' => $release['time'],
                    'type' => $release['type'] ?? "library",
                    'description' => $release['description'],
                    'homepage' => $release['homepage'],
                ]
            );

            foreach ($release['require'] as $require => $version) {
                $dependency = Dependency::firstOrCreate([
                    'name' => $require,
                    'version' => $version,
                ]);
                PackageReleaseRequire::firstOrCreate([
                    'package_id' => $this->package->id,
                    'package_release_id' => $packageRelease->id,
                    'dependency_id' => $dependency->id,
                ]);
            }
        }
    }
}
