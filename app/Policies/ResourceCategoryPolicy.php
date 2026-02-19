<?php

namespace App\Policies;

use App\Models\ResourceCategory;
use App\Models\User;

class ResourceCategoryPolicy
{
    /**
     * Determine whether the user can view any category.
     */
    public function viewAny(User $user): bool
    {
        return true; // Everyone can view categories
    }

    /**
     * Determine whether the user can view the category.
     */
    public function view(User $user, ResourceCategory $category): bool
    {
        return true; // Everyone can view categories
    }

    /**
     * Determine whether the user can create categories.
     */
    public function create(User $user): bool
    {
        return $user->hasRole('admin') || $user->hasRole('manager');
    }

    /**
     * Determine whether the user can update the category.
     */
    public function update(User $user, ResourceCategory $category): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can delete the category.
     */
    public function delete(User $user, ResourceCategory $category): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can restore the category.
     */
    public function restore(User $user, ResourceCategory $category): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can permanently delete the category.
     */
    public function forceDelete(User $user, ResourceCategory $category): bool
    {
        return $user->hasRole('admin');
    }
}
