<?php

namespace App\Http\Controllers;

use App\Models\Resource;
use App\Models\ResourceCategory;
use App\Http\Requests\StoreResourceRequest;
use App\Http\Requests\UpdateResourceRequest;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ResourceController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Resource::with('category');

        // Filter by category
        if ($request->has('category_id') && $request->category_id) {
            $query->where('resource_category_id', $request->category_id);
        }

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Filter by availability
        if ($request->has('available_only') && $request->available_only) {
            $query->available();
        }

        // Search by name
        if ($request->has('search') && $request->search) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('serial_number', 'like', '%' . $request->search . '%');
        }

        $resources = $query->latest()->paginate(15);
        $categories = ResourceCategory::all();

        return view('resources.index', compact('resources', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->authorize('create', Resource::class);
        
        $categories = ResourceCategory::all();
        return view('resources.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreResourceRequest $request)
    {
        $this->authorize('create', Resource::class);
        
        $data = $request->validated();
        
        $resource = Resource::create($data);

        logCreate($resource, 'Ressource créée');

        return redirect()->route('resources.show', $resource)
                        ->with('success', 'Ressource ajoutée avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Resource $resource)
    {
        $resource->load(['category', 'reservations' => function ($q) {
            $q->where('status', 'approved')->latest();
        }]);

        $activeReservations = $resource->getActiveReservations();
        $utilizationRate = $resource->getUtilizationRate();

        return view('resources.show', compact('resource', 'activeReservations', 'utilizationRate'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Resource $resource)
    {
        $this->authorize('update', $resource);
        
        $categories = ResourceCategory::all();
        return view('resources.edit', compact('resource', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateResourceRequest $request, Resource $resource)
    {
        $this->authorize('update', $resource);
        
        $data = $request->validated();
        
        $resource->update($data);

        logUpdate($resource, $data, 'Ressource mise à jour');

        return redirect()->route('resources.show', $resource)
                        ->with('success', 'Ressource mise à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Resource $resource)
    {
        $this->authorize('delete', $resource);

        // Check if resource has active reservations
        if ($resource->reservations()->whereIn('status', ['approved', 'active'])->exists()) {
            return back()->with('error', 'Impossible de supprimer une ressource avec des réservations actives.');
        }

        $resource->delete();

        logDelete($resource, 'Ressource supprimée');

        return redirect()->route('resources.index')
                        ->with('success', 'Ressource supprimée.');
    }

    /**
     * Set resource status to maintenance.
     */
    public function setMaintenance(Resource $resource)
    {
        $this->authorize('update', $resource);

        $resource->update(['status' => Resource::STATUS_MAINTENANCE]);

        logUpdate($resource, ['status' => 'maintenance'], 'Ressource mise en maintenance');

        return back()->with('success', 'Ressource en maintenance.');
    }

    /**
     * Set resource status to available.
     */
    public function setAvailable(Resource $resource)
    {
        $this->authorize('update', $resource);

        $resource->update(['status' => Resource::STATUS_AVAILABLE]);

        logUpdate($resource, ['status' => 'available'], 'Ressource rendue disponible');

        return back()->with('success', 'Ressource maintenant disponible.');
    }

    /**
     * Get resources by category.
     */
    public function byCategory(ResourceCategory $category)
    {
        $resources = $category->resources()->with('category')->paginate(15);
        return view('resources.index', compact('resources', 'category'));
    }
}
