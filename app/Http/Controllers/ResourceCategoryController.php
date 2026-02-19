<?php

namespace App\Http\Controllers;

use App\Models\ResourceCategory;
use App\Http\Requests\StoreResourceCategoryRequest;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ResourceCategoryController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of categories.
     */
    public function index()
    {
        $categories = ResourceCategory::withCount('resources')->paginate(15);
        return view('categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new category.
     */
    public function create()
    {
        $this->authorize('create', ResourceCategory::class);
        return view('categories.create');
    }

    /**
     * Store a newly created category in storage.
     */
    public function store(StoreResourceCategoryRequest $request)
    {
        $this->authorize('create', ResourceCategory::class);
        
        $data = $request->validated();
        
        // Generate slug if not provided
        if (!isset($data['slug']) || !$data['slug']) {
            $data['slug'] = \Illuminate\Support\Str::slug($data['name']);
        }

        $category = ResourceCategory::create($data);

        logCreate($category, 'Catégorie créée');

        return redirect()->route('categories.show', $category)
                        ->with('success', 'Catégorie créée avec succès.');
    }

    /**
     * Display the specified category.
     */
    public function show(ResourceCategory $category)
    {
        $resources = $category->resources()->paginate(15);
        return view('categories.show', compact('category', 'resources'));
    }

    /**
     * Show the form for editing the specified category.
     */
    public function edit(ResourceCategory $category)
    {
        $this->authorize('update', $category);
        return view('categories.edit', compact('category'));
    }

    /**
     * Update the specified category in storage.
     */
    public function update(Request $request, ResourceCategory $category)
    {
        $this->authorize('update', $category);
        
        $validated = $request->validate([
            'name' => 'sometimes|string|min:3|max:100|unique:resource_categories,name,' . $category->id,
            'description' => 'nullable|string|max:500',
        ]);

        $category->update($validated);

        logUpdate($category, $validated, 'Catégorie mise à jour');

        return redirect()->route('categories.show', $category)
                        ->with('success', 'Catégorie mise à jour avec succès.');
    }

    /**
     * Remove the specified category from storage.
     */
    public function destroy(ResourceCategory $category)
    {
        $this->authorize('delete', $category);

        if ($category->resources()->exists()) {
            return back()->with('error', 'Impossible de supprimer une catégorie qui contient des ressources.');
        }

        $category->delete();

        logDelete($category, 'Catégorie supprimée');

        return redirect()->route('categories.index')
                        ->with('success', 'Catégorie supprimée.');
    }
}
