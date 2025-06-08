<?php

namespace App\Console\Commands;

use App\Jobs\SyncTokenPackages;
use App\Models\Token;
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
        $tokens = Token::query()
            ->whereHas('packages')
            ->get();

        foreach ($tokens as $token) {
            SyncTokenPackages::dispatch($token);
        }

        return self::SUCCESS;
    }
}
