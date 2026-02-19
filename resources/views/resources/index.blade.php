<x-app-layout>
    <x-slot name="header">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <span>Catalogue des Ressources</span>
            @auth
                @if(auth()->user()->role === 'admin' || auth()->user()->role === 'technical_manager')
                    <a href="{{ route('resources.create') }}" class="btn btn-primary">Ajouter une ressource</a>
                @endif
            @endauth
        </div>
    </x-slot>

    <div class="card">
        <div class="card-header">
            <h3>Filtres de recherche</h3>
        </div>
        <form method="GET" action="{{ route('resources.index') }}" class="grid-4" style="align-items: end;">
            <div class="form-group">
                <label for="search" class="form-label">Recherche</label>
                <input type="text" name="search" id="search" class="form-control" value="{{ request('search') }}" placeholder="Nom ou Série...">
            </div>
            
            <div class="form-group">
                <label for="category_id" class="form-label">Catégorie</label>
                <select name="category_id" id="category_id" class="form-control">
                    <option value="">Toutes les catégories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="status" class="form-label">État</label>
                <select name="status" id="status" class="form-control">
                    <option value="">Tous les états</option>
                    <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Disponible</option>
                    <option value="in_use" {{ request('status') == 'in_use' ? 'selected' : '' }}>En cours d'utilisation</option>
                    <option value="maintenance" {{ request('status') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                    <option value="retired" {{ request('status') == 'retired' ? 'selected' : '' }}>Retiré</option>
                </select>
            </div>

            <div class="form-group">
                <button type="submit" class="btn btn-primary" style="width: 100%;">Filtrer</button>
            </div>
        </form>
    </div>

    <div class="grid-3">
        @forelse($resources as $resource)
            <div class="card" style="display: flex; flex-direction: column; height: 100%;">
                <div class="card-header">
                    <span style="font-weight: bold;">{{ $resource->name }}</span>
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
                            'maintenance' => 'Maintenance',
                            'retired' => 'Retiré',
                            default => $resource->status
                        };
                    @endphp
                    <span class="badge {{ $badgeClass }}">{{ $statusLabel }}</span>
                </div>
                <div style="flex: 1; margin-bottom: 1rem;">
                    <p><strong>Catégorie:</strong> {{ $resource->category->name }}</p>
                    <p><strong>Série:</strong> {{ $resource->serial_number }}</p>
                    <p style="margin-top: 0.5rem; color: var(--secondary-color);">{{ Str::limit($resource->description, 100) }}</p>
                </div>
                <div style="border-top: 1px solid var(--border-color); padding-top: 1rem; text-align: center;">
                    <a href="{{ route('resources.show', $resource) }}" class="btn btn-secondary" style="width: 100%;">Détails & Réservation</a>
                </div>
            </div>
        @empty
            <div class="card" style="grid-column: 1 / -1; text-align: center; padding: 3rem;">
                <p>Aucune ressource trouvée.</p>
            </div>
        @endforelse
    </div>

    <div style="margin-top: 2rem;">
        {{ $resources->links() }}
    </div>
</x-app-layout>
