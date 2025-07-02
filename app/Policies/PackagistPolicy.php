<?php

namespace App\Policies;

use App\Models\Packagist;
use App\Models\User;

class PackagistPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->status;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Packagist $packagist): bool
    {
        return $user->status;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Packagist $packagist): bool
    {
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Packagist $packagist): bool
    {
        return false;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Packagist $packagist): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Packagist $packagist): bool
    {
        return false;
    }
}
