<?php

namespace App\Policies;

use App\Models\Reservation;
use App\Models\User;

class ReservationPolicy
{
    /**
     * Determine whether the user can view any reservation.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin') || $user->hasRole('manager');
    }

    /**
     * Determine whether the user can view the reservation.
     */
    public function view(User $user, Reservation $reservation): bool
    {
        return $user->id === $reservation->user_id || 
               $user->hasRole('admin') || 
               $user->hasRole('manager');
    }

    /**
     * Determine whether the user can create reservations.
     */
    public function create(User $user): bool
    {
        return $user->is_active && in_array($user->role->name, ['user', 'manager', 'admin']);
    }

    /**
     * Determine whether the user can update the reservation.
     */
    public function update(User $user, Reservation $reservation): bool
    {
        // User can edit their own pending reservations
        if ($user->id === $reservation->user_id && $reservation->status === 'pending') {
            return true;
        }

        // Managers and admins can edit pending
        return ($user->hasRole('manager') || $user->hasRole('admin')) && $reservation->status === 'pending';
    }

    /**
     * Determine whether the user can delete the reservation.
     */
    public function delete(User $user, Reservation $reservation): bool
    {
        // Users can delete their own pending/cancelled
        if ($user->id === $reservation->user_id && in_array($reservation->status, ['pending', 'cancelled'])) {
            return true;
        }

        // Admins can delete
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can approve/decline reservations.
     */
    public function approve(User $user, Reservation $reservation): bool
    {
        return $user->hasRole('manager') || $user->hasRole('admin');
    }

    /**
     * Determine whether the user can restore the reservation.
     */
    public function restore(User $user, Reservation $reservation): bool
    {
        return $user->hasRole('admin');
    }

    /**
     * Determine whether the user can permanently delete the reservation.
     */
    public function forceDelete(User $user, Reservation $reservation): bool
    {
        return $user->hasRole('admin');
    }
}
