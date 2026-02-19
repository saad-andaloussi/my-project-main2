<x-app-layout>
    <x-slot name="header">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <span>Gestion des Catégories</span>
            @can('create', App\Models\ResourceCategory::class)
                <a href="{{ route('categories.create') }}" class="btn btn-primary">Nouvelle Catégorie</a>
            @endcan
        </div>
    </x-slot>

    <div class="card">
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Description</th>
                        <th>Ressources</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $category)
                        <tr>
                            <td>
                                <a href="{{ route('categories.show', $category) }}" style="color: var(--primary-color); font-weight: bold; text-decoration: none;">
                                    {{ $category->name }}
                                </a>
                            </td>
                            <td>{{ Str::limit($category->description, 50) }}</td>
                            <td>
                                <span class="badge badge-info">{{ $category->resources_count }} ressources</span>
                            </td>
                            <td>
                                <div style="display: flex; gap: 0.5rem;">
                                    <a href="{{ route('categories.show', $category) }}" class="btn btn-secondary" style="padding: 0.25rem 0.5rem; font-size: 0.8rem;">Voir</a>
                                    @can('update', $category)
                                        <a href="{{ route('categories.edit', $category) }}" class="btn btn-warning" style="padding: 0.25rem 0.5rem; font-size: 0.8rem;">Éditer</a>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="text-align: center; padding: 2rem; color: var(--secondary-color);">
                                Aucune catégorie trouvée.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div style="margin-top: 1rem;">
            {{ $categories->links() }}
        </div>
    </div>
</x-app-layout>
