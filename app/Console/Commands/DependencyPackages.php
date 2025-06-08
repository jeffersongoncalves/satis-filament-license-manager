<?php

namespace App\Console\Commands;

use App\Jobs\ProcessPackageDependency;
use App\Models\Package;
use Illuminate\Console\Command;

class DependencyPackages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dependency:packages';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Builds the dependency requires for all packages';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $packages = Package::all();

        foreach ($packages as $package) {
            ProcessPackageDependency::dispatch($package);
        }

        return self::SUCCESS;
    }
}
