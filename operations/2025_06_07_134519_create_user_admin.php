<?php

use App\Models\User;
use TimoKoerber\LaravelOneTimeOperations\OneTimeOperation;

return new class extends OneTimeOperation
{
    /**
     * Determine if the operation is being processed asynchronously.
     */
    protected bool $async = true;

    /**
     * The queue that the job will be dispatched to.
     */
    protected string $queue = 'default';

    /**
     * A tag name, that this operation can be filtered by.
     */
    protected ?string $tag = null;

    /**
     * Process the operation.
     */
    public function process(): void
    {
        User::create([
            'name' => 'Jefferson Simão Gonçalves',
            'email' => 'jefferson.goncalves@jsg.tec.br',
            'password' => bcrypt(config('admin.password')),
            'email_verified_at' => now(),
        ]);
    }
};
