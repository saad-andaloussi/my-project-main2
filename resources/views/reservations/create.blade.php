<x-app-layout>
    <x-slot name="header">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <span>Nouvelle demande de réservation</span>
            <a href="{{ route('reservations.index') }}" class="btn btn-secondary">Retour</a>
        </div>
    </x-slot>

    <div class="card" style="max-width: 800px; margin: 0 auto;">
        <form method="POST" action="{{ route('reservations.store') }}">
            @csrf
            
            <div class="form-group">
                <label for="resource_id" class="form-label">Ressource</label>
                <select name="resource_id" id="resource_id" class="form-control" required>
                    <option value="">Sélectionnez une ressource...</option>
                    @foreach($resources as $resource)
                        <option value="{{ $resource->id }}" {{ (old('resource_id') == $resource->id || request('resource_id') == $resource->id) ? 'selected' : '' }}>
                            {{ $resource->name }} ({{ $resource->category->name }}) - {{ $resource->price_per_hour }} €/h
                        </option>
                    @endforeach
                </select>
                @error('resource_id')
                    <span style="color: var(--danger-color); font-size: 0.875rem;">{{ $message }}</span>
                @enderror
            </div>

            <div class="grid-2">
                <div class="form-group">
                    <label for="start_time" class="form-label">Date et heure de début</label>
                    <input type="datetime-local" name="start_time" id="start_time" class="form-control" value="{{ old('start_time') }}" required>
                    @error('start_time')
                        <span style="color: var(--danger-color); font-size: 0.875rem;">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="end_time" class="form-label">Date et heure de fin</label>
                    <input type="datetime-local" name="end_time" id="end_time" class="form-control" value="{{ old('end_time') }}" required>
                    @error('end_time')
                        <span style="color: var(--danger-color); font-size: 0.875rem;">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <div class="form-group">
                <label for="justification" class="form-label">Justification de la demande (optionnel)</label>
                <textarea name="justification" id="justification" class="form-control" rows="3" placeholder="Pourquoi avez-vous besoin de cette ressource ?">{{ old('justification') }}</textarea>
                @error('justification')
                    <span style="color: var(--danger-color); font-size: 0.875rem;">{{ $message }}</span>
                @enderror
            </div>

            <div style="margin-top: 2rem; text-align: right;">
                <button type="submit" class="btn btn-primary">Soumettre la demande</button>
            </div>
        </form>
    </div>
</x-app-layout>
