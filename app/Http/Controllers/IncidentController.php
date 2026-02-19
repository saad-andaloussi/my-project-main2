<?php

namespace App\Http\Controllers;

use App\Models\Incident;
use App\Models\Resource;
use App\Http\Requests\StoreIncidentRequest;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class IncidentController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of incidents.
     */
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = auth('web')->user();
        
        // Admins see all incidents, users see only theirs
        if ($user->hasRole('admin') || $user->hasRole('manager')) {
            $incidents = Incident::with(['user', 'resource'])->latest()->paginate(15);
        } else {
            $incidents = $user->incidents()->with('resource')->latest()->paginate(15);
        }

        return view('incidents.index', compact('incidents'));
    }

    /**
     * Show the form for creating a new incident.
     */
    public function create()
    {
        $resources = Resource::active()->get();
        return view('incidents.create', compact('resources'));
    }

    /**
     * Store a newly created incident in storage.
     */
    public function store(StoreIncidentRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = auth('web')->id();
        $data['status'] = Incident::STATUS_OPEN;

        $incident = Incident::create($data);

        // Notify admins and managers about the incident
        $managers = \App\Models\User::byRole('manager')->orWhere(function ($q) {
            $q->whereHas('role', function ($role) {
                $role->where('name', 'admin');
            });
        })->get();

        foreach ($managers as $manager) {
            $manager->notify(new \App\Notifications\IncidentReported($incident));
        }

        logCreate($incident, 'Incident signalé');

        return redirect()->route('incidents.show', $incident)
                        ->with('success', 'Incident signalé avec succès. L\'équipe technique en a été notifiée.');
    }

    /**
     * Display the specified incident.
     */
    public function show(Incident $incident)
    {
        $this->authorize('view', $incident);
        return view('incidents.show', compact('incident'));
    }

    /**
     * Resolve an incident (Admin/Manager only).
     */
    public function resolve(Incident $incident)
    {
        $this->authorize('update', $incident);

        if (!in_array($incident->status, [Incident::STATUS_OPEN, Incident::STATUS_IN_PROGRESS])) {
            return back()->with('error', 'Cet incident ne peut pas être résolu.');
        }

        $incident->resolve();

        logUpdate($incident, ['status' => Incident::STATUS_RESOLVED], 'Incident résolu');

        return back()->with('success', 'Incident marqué comme résolu.');
    }

    /**
     * Close an incident.
     */
    public function close(Incident $incident)
    {
        $this->authorize('update', $incident);

        $incident->close();

        logUpdate($incident, ['status' => Incident::STATUS_CLOSED], 'Incident fermé');

        return back()->with('success', 'Incident fermé.');
    }

    /**
     * Get open incidents.
     */
    public function open()
    {
        $incidents = Incident::open()->with(['user', 'resource'])->latest()->paginate(15);
        return view('incidents.open', compact('incidents'));
    }

    /**
     * Get critical incidents.
     */
    public function critical()
    {
        $this->authorize('viewAny', Incident::class);
        
        $incidents = Incident::critical()->open()->with(['user', 'resource'])->latest()->get();
        return view('incidents.critical', compact('incidents'));
    }
}
