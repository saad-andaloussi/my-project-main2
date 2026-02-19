<x-app-layout>
    <x-slot name="header">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <span>Signaler un incident</span>
            <a href="{{ route('incidents.index') }}" class="btn btn-secondary">Retour</a>
        </div>
    </x-slot>

    <div class="card" style="max-width: 800px; margin: 0 auto;">
        <form action="{{ route('incidents.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="title" class="form-label">Titre de l'incident <span style="color: red;">*</span></label>
                <input type="text" name="title" id="title" class="form-control" value="{{ old('title') }}" required>
                @error('title')
                    <div class="alert alert-error" style="margin-top: 0.5rem;">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="resource_id" class="form-label">Ressource concernée</label>
                <select name="resource_id" id="resource_id" class="form-control">
                    <option value="">-- Sélectionner une ressource (optionnel) --</option>
                    @foreach(\App\Models\Resource::all() as $resource)
                        <option value="{{ $resource->id }}" {{ old('resource_id', request('resource_id')) == $resource->id ? 'selected' : '' }}>
                            {{ $resource->name }}
                        </option>
                    @endforeach
                </select>
                @error('resource_id')
                    <div class="alert alert-error" style="margin-top: 0.5rem;">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="severity" class="form-label">Gravité <span style="color: red;">*</span></label>
                <select name="severity" id="severity" class="form-control" required>
                    <option value="low" {{ old('severity') == 'low' ? 'selected' : '' }}>Basse</option>
                    <option value="medium" {{ old('severity') == 'medium' ? 'selected' : '' }}>Moyenne</option>
                    <option value="high" {{ old('severity') == 'high' ? 'selected' : '' }}>Haute</option>
                    <option value="critical" {{ old('severity') == 'critical' ? 'selected' : '' }}>Critique</option>
                </select>
                @error('severity')
                    <div class="alert alert-error" style="margin-top: 0.5rem;">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="description" class="form-label">Description détaillée <span style="color: red;">*</span></label>
                <textarea name="description" id="description" class="form-control" rows="6" required>{{ old('description') }}</textarea>
                @error('description')
                    <div class="alert alert-error" style="margin-top: 0.5rem;">{{ $message }}</div>
                @enderror
            </div>

            <div style="margin-top: 2rem; display: flex; justify-content: flex-end;">
                <button type="submit" class="btn btn-danger">Signaler l'incident</button>
            </div>
        </form>
    </div>
</x-app-layout>
