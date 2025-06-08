<?php

namespace App\Observers;

use App\Jobs\SyncTokenPackages;
use App\Models\Token;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Cache;
use Psr\SimpleCache\InvalidArgumentException;

class TokenObserver
{
    public function creating(Token $token): void
    {
        $tokenGenerate = Token::generateCode('token');
        $token->setAttribute('password', $tokenGenerate);
        $token->setAttribute('token', $tokenGenerate);
    }

    /**
     * Handle the Token "created" event.
     */
    public function created(Token $token): void
    {
        try {
            Cache::delete('tokens_count');
        } catch (InvalidArgumentException) {
        }
        SyncTokenPackages::dispatch($token);
    }

    /**
     * Handle the Token "updated" event.
     */
    public function updated(Token $token): void
    {
        SyncTokenPackages::dispatch($token);
    }

    /**
     * Handle the Token "deleted" event.
     */
    public function deleted(Token $token): void
    {
        try {
            Cache::delete('tokens_count');
        } catch (InvalidArgumentException) {
        }
        app(Filesystem::class)->deleteDirectory(storage_path("app/private/satis/{$token->id}"));
    }

    /**
     * Handle the Token "restored" event.
     */
    public function restored(Token $token): void
    {
        //
    }

    /**
     * Handle the Token "force deleted" event.
     */
    public function forceDeleted(Token $token): void
    {
        //
    }
}
