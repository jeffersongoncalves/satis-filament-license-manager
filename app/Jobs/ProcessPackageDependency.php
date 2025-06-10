<?php

namespace App\Jobs;

use App\Actions\ProcessPackageFilename;
use App\Models\Package;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

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
    public function handle(ProcessPackageFilename $processPackageFilename): void
    {
        $processPackageFilename->execute($this->package);
    }
}
