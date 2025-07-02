<?php

namespace App\Jobs;

use App\Models\Package;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class DownloadComposerJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(
        protected readonly string $package,
        protected readonly string $version,
    ) {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $package = Package::query()->where('name', $this->package)->first();
        $package?->downloadComposer($this->version);
    }
}
