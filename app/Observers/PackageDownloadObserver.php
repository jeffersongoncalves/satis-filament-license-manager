<?php

namespace App\Observers;

use App\Models\PackageDownload;
use Illuminate\Support\Facades\Cache;
use Psr\SimpleCache\InvalidArgumentException;

class PackageDownloadObserver
{
    /**
     * Handle the PackageDownload "created" event.
     */
    public function created(PackageDownload $packageDownload): void
    {
        try {
            Cache::delete('package_downloads_count');
        } catch (InvalidArgumentException) {
        }
    }

    /**
     * Handle the PackageDownload "updated" event.
     */
    public function updated(PackageDownload $packageDownload): void
    {
        //
    }

    /**
     * Handle the PackageDownload "deleted" event.
     */
    public function deleted(PackageDownload $packageDownload): void
    {
        try {
            Cache::delete('package_downloads_count');
        } catch (InvalidArgumentException) {
        }
    }

    /**
     * Handle the PackageDownload "restored" event.
     */
    public function restored(PackageDownload $packageDownload): void
    {
        //
    }

    /**
     * Handle the PackageDownload "force deleted" event.
     */
    public function forceDeleted(PackageDownload $packageDownload): void
    {
        //
    }
}
