<?php

namespace App\Policies;

use App\Models\Resource;
use App\Models\User;

class ResourcePolicy
{
    /**
     * Determine whether the user can view any resource.
     */
    public function viewAny(User $user): bool
    {
        return true; // Everyone can view resources
    }

    /**
     * Determine whether the user can view the resource.
     */
    public function view(User $user, Resource $resource): bool
    {
        return true; // Everyone can view resources
    }

    /**
     * Determine whether the user can create resources.
     */
    public function create(User $user): bool
    {
        return $user->hasRole('admin') || $user->hasRole('manager');
    }

    /**
     * Determine whether the user can update the resource.
     */
    public function update(User $user, Resource $resource): bool
    {
        return $user->hasRole('admin') || $user->hasRole('manager');
    }

    /**
     * Determine whether the user can delete the resource.
     */
    public function delete(User $user, Resource $resource): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can restore the resource.
     */
    public function restore(User $user, Resource $resource): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can permanently delete the resource.
     */
    public function forceDelete(User $user, Resource $resource): bool
    {
        return $user->hasRole('admin');
    }
}
