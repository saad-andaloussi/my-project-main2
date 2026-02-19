<x-app-layout>
    <x-slot name="header">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <span>{{ $category->name }}</span>
            <div style="display: flex; gap: 0.5rem;">
                @can('update', $category)
                    <a href="{{ route('categories.edit', $category) }}" class="btn btn-warning">Éditer</a>
                @endcan
                <a href="{{ route('categories.index') }}" class="btn btn-secondary">Retour</a>
            </div>
        </div>
    </x-slot>

    <div class="card">
        <div class="card-header">
            <h3>Détails</h3>
        </div>
        <div class="grid-2">
            <div>
                <strong>Nom :</strong>
                <p>{{ $category->name }}</p>
            </div>
            <div>
                <strong>Slug :</strong>
                <p style="font-family: monospace; color: var(--secondary-color);">{{ $category->slug }}</p>
            </div>
            <div style="grid-column: span 2;">
                <strong>Description :</strong>
                <p>{{ $category->description ?? 'Aucune description.' }}</p>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <div style="display: flex; justify-content: space-between; align-items: center; width: 100%;">
                <h3>Ressources associées</h3>
                @if(auth()->user()->role === 'admin' || auth()->user()->role === 'technical_manager')
                    <a href="{{ route('resources.create', ['category_id' => $category->id]) }}" class="btn btn-primary btn-sm">Ajouter une ressource</a>
                @endif
            </div>
        </div>

        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>État</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($resources as $resource)
                        <tr>
                            <td>
                                <a href="{{ route('resources.show', $resource) }}" style="color: var(--primary-color); font-weight: bold; text-decoration: none;">
                                    {{ $resource->name }}
                                </a>
                            </td>
                            <td>
                                @php
                                    $badgeClass = match($resource->status) {
                                        'available' => 'badge-success',
                                        'in_use' => 'badge-warning',
                                        'maintenance' => 'badge-danger',
                                        'retired' => 'badge-secondary',
                                        default => 'badge-info'
                                    };
                                    $statusLabel = match($resource->status) {
                                        'available' => 'Disponible',
                                        'in_use' => 'En cours d\'utilisation',
                                        'maintenance' => 'En maintenance',
                                        'retired' => 'Retiré',
                                        default => $resource->status
                                    };
                                @endphp
                                <span class="badge {{ $badgeClass }}">{{ $statusLabel }}</span>
                            </td>
                            <td>
                                <a href="{{ route('resources.show', $resource) }}" class="btn btn-secondary" style="padding: 0.25rem 0.5rem; font-size: 0.8rem;">Voir</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" style="text-align: center; padding: 2rem; color: var(--secondary-color);">
                                Aucune ressource dans cette catégorie.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div style="margin-top: 1rem;">
            {{ $resources->links() }}
        </div>
    </div>
</x-app-layout>
