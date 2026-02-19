<?php

namespace App\Services;

use App\Models\Reservation;
use App\Models\Resource;
use Carbon\Carbon;

class ReservationService
{
    /**
     * Create a new reservation with automatic price calculation.
     */
    public function createReservation(array $data)
    {
        $resource = Resource::findOrFail($data['resource_id']);

        // Verify resource availability
        if (!$resource->isAvailable() && !in_array($resource->status, ['available', 'in_use'])) {
            throw new \Exception('La ressource n\'est pas disponible.');
        }

        // Check for conflicts
        if ($resource->hasConflict($data['start_time'], $data['end_time'])) {
            throw new \Exception('Conflit de réservation: La ressource est déjà réservée pour cette période.');
        }

        // Create the reservation
        $reservation = new Reservation($data);
        $reservation->status = Reservation::STATUS_PENDING;
        $reservation->save();

        return $reservation;
    }

    /**
     * Approve a reservation and update resource status if needed.
     */
    public function approveReservation(Reservation $reservation)
    {
        if ($reservation->status !== Reservation::STATUS_PENDING) {
            throw new \Exception('Seules les réservations en attente peuvent être approuvées.');
        }

        $reservation->approve();

        // If this is an active period, update resource status
        if ($reservation->start_time <= Carbon::now() && $reservation->end_time >= Carbon::now()) {
            $reservation->resource->update(['status' => Resource::STATUS_IN_USE]);
        }

        return $reservation;
    }

    /**
     * Decline a reservation with notification.
     */
    public function declineReservation(Reservation $reservation, $reason = null)
    {
        if ($reservation->status !== Reservation::STATUS_PENDING) {
            throw new \Exception('Seules les réservations en attente peuvent être refusées.');
        }

        $reservation->decline();

        // TODO: Send notification to user with reason
        
        return $reservation;
    }

    /**
     * Cancel a reservation.
     */
    public function cancelReservation(Reservation $reservation, $reason = null)
    {
        if (in_array($reservation->status, [Reservation::STATUS_DECLINED, Reservation::STATUS_COMPLETED])) {
            throw new \Exception('Cette réservation ne peut pas être annulée.');
        }

        $reservation->cancel();

        return $reservation;
    }

    /**
     * Activate a reservation (when it starts).
     */
    public function activateReservation(Reservation $reservation)
    {
        if ($reservation->status !== Reservation::STATUS_APPROVED) {
            throw new \Exception('Seules les réservations approuvées peuvent être activées.');
        }

        if (Carbon::now() < $reservation->start_time) {
            throw new \Exception('La réservation n\'a pas encore commencé.');
        }

        $reservation->activate();
        $reservation->resource->update(['status' => Resource::STATUS_IN_USE]);

        return $reservation;
    }

    /**
     * Complete a reservation (when it ends).
     */
    public function completeReservation(Reservation $reservation)
    {
        if ($reservation->status !== Reservation::STATUS_ACTIVE) {
            throw new \Exception('Seules les réservations actives peuvent être complétées.');
        }

        $reservation->complete();
        
        // Set resource back to available
        $reservation->resource->update(['status' => Resource::STATUS_AVAILABLE]);

        return $reservation;
    }

    /**
     * Get available resources for a given period.
     */
    public function getAvailableResources(Carbon $startTime, Carbon $endTime, $categoryId = null)
    {
        $query = Resource::available();

        if ($categoryId) {
            $query->where('resource_category_id', $categoryId);
        }

        $allResources = $query->get();

        // Filter out resources with conflicts
        return $allResources->filter(function ($resource) use ($startTime, $endTime) {
            return !$resource->hasConflict($startTime, $endTime);
        })->values();
    }

    /**
     * Check for reservation conflicts.
     */
    public function hasConflict(Resource $resource, Carbon $startTime, Carbon $endTime, $excludeReservationId = null)
    {
        return $resource->hasConflict($startTime, $endTime, $excludeReservationId);
    }

    /**
     * Get upcoming reservations (next 7 days).
     */
    public function getUpcomingReservations($limit = 10)
    {
        return Reservation::whereIn('status', [Reservation::STATUS_APPROVED, Reservation::STATUS_ACTIVE])
            ->where('start_time', '>', Carbon::now())
            ->where('start_time', '<=', Carbon::now()->addDays(7))
            ->orderBy('start_time')
            ->limit($limit)
            ->get();
    }

    /**
     * Get expired reservations (past and not completed).
     */
    public function getExpiredReservations()
    {
        return Reservation::whereIn('status', [Reservation::STATUS_APPROVED, Reservation::STATUS_ACTIVE])
            ->where('end_time', '<', Carbon::now())
            ->get();
    }

    /**
     * Calculate statistics.
     */
    public function getStatistics()
    {
        return [
            'total_reservations' => Reservation::count(),
            'pending' => Reservation::pending()->count(),
            'approved' => Reservation::approved()->count(),
            'active' => Reservation::active()->count(),
            'completed' => Reservation::where('status', Reservation::STATUS_COMPLETED)->count(),
            'average_price' => Reservation::avg('total_price'),
            'total_revenue' => Reservation::where('payment', 'paid')->sum('total_price'),
        ];
    }
}
