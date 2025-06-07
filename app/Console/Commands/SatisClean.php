<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class SatisClean extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'satis:clean';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cleans all the Satis repositories';

    /**
     * Execute the console command.
     */
    public function handle(Filesystem $filesystem): int
    {
        $filesystem->deleteDirectory(storage_path('app/private/satis'));

        $this->info('Satis repositories cleaned successfully.');

        return self::SUCCESS;
    }
}
