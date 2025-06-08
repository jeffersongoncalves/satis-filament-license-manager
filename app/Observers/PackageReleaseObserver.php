<?php

namespace App\Observers;

use App\Models\PackageRelease;
use Illuminate\Support\Facades\Cache;
use Psr\SimpleCache\InvalidArgumentException;

class PackageReleaseObserver
{
    /**
     * Handle the PackageRelease "created" event.
     */
    public function created(PackageRelease $packageRelease): void
    {
        try {
            Cache::delete('package_releases_count');
        } catch (InvalidArgumentException) {
        }
    }

    /**
     * Handle the PackageRelease "updated" event.
     */
    public function updated(PackageRelease $packageRelease): void
    {
        //
    }

    /**
     * Handle the PackageRelease "deleted" event.
     */
    public function deleted(PackageRelease $packageRelease): void
    {
        try {
            Cache::delete('package_releases_count');
        } catch (InvalidArgumentException) {
        }
    }

    /**
     * Handle the PackageRelease "restored" event.
     */
    public function restored(PackageRelease $packageRelease): void
    {
        //
    }

    /**
     * Handle the PackageRelease "force deleted" event.
     */
    public function forceDeleted(PackageRelease $packageRelease): void
    {
        //
    }
}
