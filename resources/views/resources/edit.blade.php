<x-app-layout>
    <x-slot name="header">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <span>Modifier la ressource : {{ $resource->name }}</span>
            <a href="{{ route('resources.show', $resource) }}" class="btn btn-secondary">Retour</a>
        </div>
    </x-slot>

    <div class="card" style="max-width: 800px; margin: 0 auto;">
        <form method="POST" action="{{ route('resources.update', $resource) }}">
            @csrf
            @method('PATCH')

            <div class="form-group">
                <label for="name" class="form-label">Nom de la ressource</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $resource->name) }}" required>
                @error('name')
                    <span style="color: var(--danger-color); font-size: 0.875rem;">{{ $message }}</span>
                @enderror
            </div>

            <div class="grid-2">
                <div class="form-group">
                    <label for="resource_category_id" class="form-label">Catégorie</label>
                    <select name="resource_category_id" id="resource_category_id" class="form-control" required>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('resource_category_id', $resource->resource_category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('resource_category_id')
                        <span style="color: var(--danger-color); font-size: 0.875rem;">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="serial_number" class="form-label">Numéro de série</label>
                    <input type="text" name="serial_number" id="serial_number" class="form-control" value="{{ old('serial_number', $resource->serial_number) }}">
                    @error('serial_number')
                        <span style="color: var(--danger-color); font-size: 0.875rem;">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label for="description" class="form-label">Description</label>
                <textarea name="description" id="description" class="form-control" rows="4">{{ old('description', $resource->description) }}</textarea>
                @error('description')
                    <span style="color: var(--danger-color); font-size: 0.875rem;">{{ $message }}</span>
                @enderror
            </div>

            <div class="grid-3">
                <div class="form-group">
                    <label for="cpu_cores" class="form-label">CPU Cores</label>
                    <input type="number" name="cpu_cores" id="cpu_cores" class="form-control" value="{{ old('cpu_cores', $resource->cpu_cores) }}">
                </div>
                <div class="form-group">
                    <label for="ram_gb" class="form-label">RAM (GB)</label>
                    <input type="number" name="ram_gb" id="ram_gb" class="form-control" value="{{ old('ram_gb', $resource->ram_gb) }}">
                </div>
                <div class="form-group">
                    <label for="storage_gb" class="form-label">Stockage (GB)</label>
                    <input type="number" name="storage_gb" id="storage_gb" class="form-control" value="{{ old('storage_gb', $resource->storage_gb) }}">
                </div>
            </div>

            <div class="grid-2">
                <div class="form-group">
                    <label for="purchase_price" class="form-label">Prix d'achat (€)</label>
                    <input type="number" step="0.01" name="purchase_price" id="purchase_price" class="form-control" value="{{ old('purchase_price', $resource->purchase_price) }}" required>
                    @error('purchase_price')
                        <span style="color: var(--danger-color); font-size: 0.875rem;">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="price_per_hour" class="form-label">Prix par heure (€)</label>
                    <input type="number" step="0.01" name="price_per_hour" id="price_per_hour" class="form-control" value="{{ old('price_per_hour', $resource->price_per_hour) }}" required>
                    @error('price_per_hour')
                        <span style="color: var(--danger-color); font-size: 0.875rem;">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label for="status" class="form-label">État</label>
                <select name="status" id="status" class="form-control" required>
                    <option value="available" {{ old('status', $resource->status) == 'available' ? 'selected' : '' }}>Disponible</option>
                    <option value="in_use" {{ old('status', $resource->status) == 'in_use' ? 'selected' : '' }}>En cours d'utilisation</option>
                    <option value="maintenance" {{ old('status', $resource->status) == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                    <option value="retired" {{ old('status', $resource->status) == 'retired' ? 'selected' : '' }}>Retiré</option>
                </select>
                @error('status')
                    <span style="color: var(--danger-color); font-size: 0.875rem;">{{ $message }}</span>
                @enderror
            </div>

            <div style="margin-top: 2rem; display: flex; justify-content: space-between;">
                <!-- Delete Button -->
                <button type="button" class="btn btn-danger" onclick="if(confirm('Êtes-vous sûr de vouloir supprimer cette ressource ?')) document.getElementById('delete-form').submit();">
                    Supprimer
                </button>
                
                <button type="submit" class="btn btn-primary">Mettre à jour</button>
            </div>
        </form>

        <form id="delete-form" action="{{ route('resources.destroy', $resource) }}" method="POST" style="display: none;">
            @csrf
            @method('DELETE')
        </form>
    </div>
</x-app-layout>
