<?php

namespace App\Observers;

use App\Enums\DependencyType;
use App\Models\Dependency;
use App\Models\Packagist;
use Illuminate\Support\Facades\Cache;
use Psr\SimpleCache\InvalidArgumentException;

class DependencyObserver
{
    /**
     * Handle the Dependency "creating" event.
     */
    public function creating(Dependency $dependency): void
    {
        if (str($dependency->name)->contains('/')) {
            $dependency->setAttribute('type', Packagist::getDependencyType($dependency->name));
        } else {
            $dependency->setAttribute('type', DependencyType::Public);
        }
    }

    /**
     * Handle the Dependency "created" event.
     */
    public function created(Dependency $dependency): void
    {
        try {
            Cache::delete('dependencies_count');
        } catch (InvalidArgumentException) {
        }
    }

    /**
     * Handle the Dependency "updated" event.
     */
    public function updated(Dependency $dependency): void
    {
        //
    }

    /**
     * Handle the Dependency "deleted" event.
     */
    public function deleted(Dependency $dependency): void
    {
        try {
            Cache::delete('dependencies_count');
        } catch (InvalidArgumentException) {
        }
    }

    /**
     * Handle the Dependency "restored" event.
     */
    public function restored(Dependency $dependency): void
    {
        //
    }

    /**
     * Handle the Dependency "force deleted" event.
     */
    public function forceDeleted(Dependency $dependency): void
    {
        //
    }
}
