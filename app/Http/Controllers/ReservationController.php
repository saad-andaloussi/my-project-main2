<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Resource;
use App\Http\Requests\StoreReservationRequest;
use App\Http\Requests\UpdateReservationRequest;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ReservationController extends Controller
{
    use AuthorizesRequests;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = auth('web')->user();
        
        // Admins and managers see all reservations, users see only theirs
        if ($user->hasRole('admin') || $user->hasRole('manager')) {
            $reservations = Reservation::with(['user', 'resource'])->latest()->paginate(15);
        } else {
            $reservations = $user->reservations()->with('resource')->latest()->paginate(15);
        }

        return view('reservations.index', compact('reservations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $resources = Resource::available()->with('category')->get();
        return view('reservations.create', compact('resources'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreReservationRequest $request)
    {
        $data = $request->validated();
        $resource = Resource::findOrFail($data['resource_id']);

        // Normalize times to Carbon instances (HTML datetime-local -> Y-m-d\TH:i)
        $start = Carbon::createFromFormat('Y-m-d\TH:i', $data['start_time']);
        $end = Carbon::createFromFormat('Y-m-d\TH:i', $data['end_time']);

        // Check for conflicts
        if ($resource->hasConflict($start, $end)) {
            return back()->with('error', 'Cette ressource n\'est pas disponible pour la période choisie. Veuillez sélectionner une autre date.');
        }

        // Persist normalized times
        $data['start_time'] = $start;
        $data['end_time'] = $end;

        // Create reservation
        /** @var \App\Models\User $user */
        $user = auth('web')->user();
        $reservation = $user->reservations()->create($data);

        // Log the action
        logCreate($reservation, 'Réservation créée');

        return redirect()->route('reservations.show', $reservation)
                        ->with('success', 'Réservation créée avec succès. En attente d\'approbation.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Reservation $reservation)
    {
        $this->authorize('view', $reservation);
        return view('reservations.show', compact('reservation'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Reservation $reservation)
    {
        $this->authorize('update', $reservation);
        
        // Only pending reservations can be edited
        if ($reservation->status !== 'pending') {
            return back()->with('error', 'Seules les réservations en attente peuvent être modifiées.');
        }

        $resources = Resource::available()->with('category')->get();
        return view('reservations.edit', compact('reservation', 'resources'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateReservationRequest $request, Reservation $reservation)
    {
        $this->authorize('update', $reservation);

        if ($reservation->status !== 'pending') {
            return back()->with('error', 'Seules les réservations en attente peuvent être modifiées.');
        }

        $data = $request->validated();
        
        // Check for conflicts if times are being updated
        if (isset($data['start_time']) || isset($data['end_time'])) {
            $startTime = isset($data['start_time'])
                ? Carbon::createFromFormat('Y-m-d\TH:i', $data['start_time'])
                : $reservation->start_time;
            $endTime = isset($data['end_time'])
                ? Carbon::createFromFormat('Y-m-d\TH:i', $data['end_time'])
                : $reservation->end_time;
            
            if ($reservation->resource->hasConflict($startTime, $endTime, $reservation->id)) {
                return back()->with('error', 'La période choisie n\'est pas disponible.');
            }

            // Persist normalized times if provided
            if (isset($data['start_time'])) {
                $data['start_time'] = $startTime;
            }
            if (isset($data['end_time'])) {
                $data['end_time'] = $endTime;
            }
        }

        $reservation->update($data);

        logUpdate($reservation, $data, 'Réservation mise à jour');

        return redirect()->route('reservations.show', $reservation)
                        ->with('success', 'Réservation mise à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Reservation $reservation)
    {
        $this->authorize('delete', $reservation);

        if (!in_array($reservation->status, ['pending', 'cancelled'])) {
            return back()->with('error', 'Vous ne pouvez pas supprimer cette réservation.');
        }

        logDelete($reservation, 'Réservation supprimée');

        $reservation->delete();

        return redirect()->route('reservations.index')
                        ->with('success', 'Réservation supprimée.');
    }

    /**
     * Approve a reservation (Admin/Manager only).
     */
    public function approve(Reservation $reservation)
    {
        $this->authorize('approve', $reservation);

        if ($reservation->status !== 'pending') {
            return back()->with('error', 'Seules les réservations en attente peuvent être approuvées.');
        }

        $reservation->approve();

        // Send notification to user
        $reservation->user->notify(new \App\Notifications\ReservationApproved($reservation));

        logCreate($reservation, 'Réservation approuvée');

        return back()->with('success', 'Réservation approuvée et notification envoyée.');
    }

    /**
     * Decline a reservation (Admin/Manager only).
     */
    public function decline(Reservation $reservation)
    {
        $this->authorize('approve', $reservation);

        if ($reservation->status !== 'pending') {
            return back()->with('error', 'Seules les réservations en attente peuvent être refusées.');
        }

        $reservation->decline();

        // Send notification to user
        $reservation->user->notify(new \App\Notifications\ReservationDeclined($reservation));

        logCreate($reservation, 'Réservation refusée');

        return back()->with('success', 'Réservation refusée et notification envoyée.');
    }

    /**
     * Get user's pending reservations.
     */
    public function pending()
    {
        /** @var \App\Models\User $user */
        $user = auth('web')->user();
        $reservations = $user->reservations()->pending()->get();
        return view('reservations.pending', compact('reservations'));
    }

    /**
     * Get user's approved reservations.
     */
    public function approved()
    {
        /** @var \App\Models\User $user */
        $user = auth('web')->user();
        $reservations = $user->reservations()->approved()->get();
        return view('reservations.approved', compact('reservations'));
    }
}
