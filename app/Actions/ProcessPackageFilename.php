<?php

namespace App\Actions;

use App\Models\Dependency;
use App\Models\DependencyPackageRelease;
use App\Models\Package;
use App\Models\PackageRelease;
use Illuminate\Support\Facades\File;

class ProcessPackageFilename
{
    public function execute(Package $package): void
    {
        if (! File::exists($filename = storage_path('app/private/composer/cache/repo/'.$package->folder.'/provider-'.$package->name_provider.'.json'))) {
            if (! File::exists($filename = storage_path('app/private/composer/cache/repo/'.$package->folder.'/packages.json'))) {
                return;
            }
        }
        $file = File::json($filename);
        if (! isset($file['packages'][$package->name])) {
            return;
        }
        $releases = $file['packages'][$package->name];
        foreach ($releases as $release) {
            $packageRelease = PackageRelease::firstOrCreate([
                'package_id' => $package->id,
                'version' => $release['version'],
            ], [
                'time' => $release['time'],
                'type' => ! empty($release['type']) ? $release['type'] : 'library',
                'description' => ! empty($release['description']) ? $release['description'] : '',
                'homepage' => ! empty($release['homepage']) ? $release['homepage'] : '',
            ]);

            foreach ($release['require'] as $require => $version) {
                $version = is_array($version) ? implode(',', $version) : $version;
                $dependency = Dependency::firstOrCreate([
                    'name' => $require,
                ], [
                    'versions' => [$version],
                ]);
                $versions = collect($dependency->versions ?? []);
                if (! $versions->contains($version)) {
                    $versions->push($version);
                    $dependency->update([
                        'versions' => $versions->unique()->values()->all(),
                    ]);
                }
                DependencyPackageRelease::firstOrCreate([
                    'package_id' => $package->id,
                    'package_release_id' => $packageRelease->id,
                    'dependency_id' => $dependency->id,
                    'version' => $version,
                ]);
            }
        }
    }
}
