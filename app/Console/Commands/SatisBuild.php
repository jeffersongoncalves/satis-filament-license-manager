<?php

namespace App\Console\Commands;

use App\Jobs\SyncPackages;
use Illuminate\Console\Command;

class SatisBuild extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'satis:build';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Builds the Satis repository for all packages';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        SyncPackages::dispatch();

        return self::SUCCESS;
    }
}
