<x-app-layout>
    <x-slot name="header">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <span>Modifier la réservation #{{ $reservation->id }}</span>
            <a href="{{ route('reservations.show', $reservation) }}" class="btn btn-secondary">Retour</a>
        </div>
    </x-slot>

    <div class="card" style="max-width: 800px; margin: 0 auto;">
        @if($reservation->status !== 'pending')
            <div class="alert alert-warning">
                Attention : Seules les réservations en attente peuvent être modifiées. Cette réservation est actuellement <strong>{{ $reservation->status }}</strong>.
            </div>
        @else
            <form action="{{ route('reservations.update', $reservation) }}" method="POST">
                @csrf
                @method('PATCH')

                <!-- Resource Selection -->
                <div class="form-group">
                    <label for="resource_id" class="form-label">Ressource <span style="color: red;">*</span></label>
                    <select name="resource_id" id="resource_id" class="form-control" required>
                        <option value="">-- Sélectionner une ressource --</option>
                        @foreach($resources as $resource)
                            <option value="{{ $resource->id }}" {{ old('resource_id', $reservation->resource_id) == $resource->id ? 'selected' : '' }}>
                                {{ $resource->name }} ({{ $resource->category->name ?? 'Sans catégorie' }})
                            </option>
                        @endforeach
                        <!-- If current resource is not available/visible in the list (e.g. became unavailable), keep it as an option if selected -->
                        @if(!$resources->contains('id', $reservation->resource_id))
                            <option value="{{ $reservation->resource_id }}" selected>
                                {{ $reservation->resource->name }} (Actuelle)
                            </option>
                        @endif
                    </select>
                    @error('resource_id')
                        <div class="alert alert-error" style="margin-top: 0.5rem;">{{ $message }}</div>
                    @enderror
                </div>

                <div class="grid-2">
                    <!-- Start Date/Time -->
                    <div class="form-group">
                        <label for="start_time" class="form-label">Date de début <span style="color: red;">*</span></label>
                        <input type="datetime-local" name="start_time" id="start_time" class="form-control" 
                            value="{{ old('start_time', $reservation->start_time->format('Y-m-d\TH:i')) }}" required>
                        @error('start_time')
                            <div class="alert alert-error" style="margin-top: 0.5rem;">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- End Date/Time -->
                    <div class="form-group">
                        <label for="end_time" class="form-label">Date de fin <span style="color: red;">*</span></label>
                        <input type="datetime-local" name="end_time" id="end_time" class="form-control" 
                            value="{{ old('end_time', $reservation->end_time->format('Y-m-d\TH:i')) }}" required>
                        @error('end_time')
                            <div class="alert alert-error" style="margin-top: 0.5rem;">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label for="notes" class="form-label">Notes / Motif</label>
                    <textarea name="notes" id="notes" class="form-control" rows="3">{{ old('notes', $reservation->notes) }}</textarea>
                    @error('notes')
                        <div class="alert alert-error" style="margin-top: 0.5rem;">{{ $message }}</div>
                    @enderror
                </div>

                <div style="margin-top: 2rem; display: flex; justify-content: space-between; align-items: center;">
                    <div>
                         <button type="button" class="btn btn-danger" onclick="if(confirm('Êtes-vous sûr de vouloir annuler cette réservation ?')) document.getElementById('cancel-form').submit();">
                            Annuler la réservation
                        </button>
                    </div>
                    <button type="submit" class="btn btn-primary">Mettre à jour</button>
                </div>
            </form>

            <form id="cancel-form" action="{{ route('reservations.destroy', $reservation) }}" method="POST" style="display: none;">
                @csrf
                @method('DELETE')
            </form>
        @endif
    </div>
</x-app-layout>
