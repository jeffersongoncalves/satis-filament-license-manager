<?php

namespace App\Jobs;

use App\Models\Package;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class AddDependencyDefaultByPackage implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        protected readonly Package $package,
        protected readonly string $package_dependency,
    ) {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if (Package::query()->where('name', $this->package_dependency)->exists()) {
            return;
        }
        Package::create([
            'name' => $this->package_dependency,
            'type' => $this->package->type,
            'url' => $this->package->url,
            'username' => $this->package->username,
            'password' => $this->package->password,
        ]);
    }
}
