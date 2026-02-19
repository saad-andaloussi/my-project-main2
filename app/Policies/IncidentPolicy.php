<?php

namespace App\Policies;

use App\Models\Incident;
use App\Models\User;

class IncidentPolicy
{
    /**
     * Determine whether the user can view any incident.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin') || $user->hasRole('manager');
    }

    /**
     * Determine whether the user can view the incident.
     */
    public function view(User $user, Incident $incident): bool
    {
        return $user->id === $incident->user_id || 
               $user->hasRole('admin') || 
               $user->hasRole('manager');
    }

    /**
     * Determine whether the user can create incidents.
     */
    public function create(User $user): bool
    {
        return $user->is_active;
    }

    /**
     * Determine whether the user can update the incident.
     */
    public function update(User $user, Incident $incident): bool
    {
        return $user->hasRole('manager') || $user->hasRole('admin');
    }

    /**
     * Determine whether the user can delete the incident.
     */
    public function delete(User $user, Incident $incident): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can restore the incident.
     */
    public function restore(User $user, Incident $incident): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can permanently delete the incident.
     */
    public function forceDelete(User $user, Incident $incident): bool
    {
        return $user->hasRole('admin');
    }
}
