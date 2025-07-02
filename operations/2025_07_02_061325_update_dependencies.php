<?php

use App\Enums\DependencyType;
use App\Models\Dependency;
use App\Models\Packagist;
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
        $dependencies = Dependency::query()->get();

        foreach ($dependencies as $dependency) {
            if (str($dependency->name)->contains('/')) {
                $dependency->update(['type' => Packagist::getDependencyType($dependency->name)]);
            } else {
                $dependency->update(['type' => DependencyType::Public]);
            }
        }
    }
};
