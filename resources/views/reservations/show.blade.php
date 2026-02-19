<x-app-layout>
    <x-slot name="header">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <span>Détails de la réservation #{{ $reservation->id }}</span>
            <a href="{{ route('reservations.index') }}" class="btn btn-secondary">Retour</a>
        </div>
    </x-slot>

    <div class="grid-3">
        <div class="card" style="grid-column: span 2;">
            <div class="card-header">
                <h3>Informations</h3>
                @php
                    $badgeClass = match($reservation->status) {
                        'pending' => 'badge-warning',
                        'approved' => 'badge-success',
                        'declined' => 'badge-danger',
                        'active' => 'badge-info',
                        'completed' => 'badge-secondary',
                        default => 'badge-info'
                    };
                    $statusLabel = match($reservation->status) {
                        'pending' => 'En attente',
                        'approved' => 'Approuvée',
                        'declined' => 'Refusée',
                        'active' => 'Active',
                        'completed' => 'Terminée',
                        default => $reservation->status
                    };
                @endphp
                <span class="badge {{ $badgeClass }}">{{ $statusLabel }}</span>
            </div>

            <div class="grid-2">
                <div>
                    <p><strong>Ressource :</strong> <a href="{{ route('resources.show', $reservation->resource) }}">{{ $reservation->resource->name }}</a></p>
                    <p><strong>Demandé par :</strong> {{ $reservation->user->name }}</p>
                    <p><strong>Date de demande :</strong> {{ $reservation->created_at->format('d/m/Y H:i') }}</p>
                </div>
                <div>
                    <p><strong>Début :</strong> {{ $reservation->start_time }}</p>
                    <p><strong>Fin :</strong> {{ $reservation->end_time }}</p>
                </div>
            </div>

            <div style="margin-top: 1.5rem;">
                <p><strong>Justification :</strong></p>
                <p style="background: var(--bg-color); padding: 1rem; border-radius: 4px; margin-top: 0.5rem;">
                    {{ $reservation->notes ?? 'Aucune justification fournie.' }}
                </p>
            </div>

            <!-- Approval/Rejection Actions for Admin/Manager -->
            @if((auth()->user()->role === 'admin' || auth()->user()->role === 'technical_manager') && $reservation->status === 'pending')
                <div style="margin-top: 2rem; padding-top: 1rem; border-top: 1px solid var(--border-color);">
                    <h3>Gestion de la demande</h3>
                    <div style="display: flex; gap: 1rem; margin-top: 1rem;">
                        <!-- Assuming there are routes or a method to approve/decline. 
                             If not implemented in backend yet, this is placeholder UI -->
                        <form action="{{ route('reservations.update', $reservation) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="approved">
                            <button type="submit" class="btn btn-success">Approuver</button>
                        </form>

                        <form action="{{ route('reservations.update', $reservation) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="status" value="declined">
                            <button type="submit" class="btn btn-danger">Refuser</button>
                        </form>
                    </div>
                </div>
            @endif
        </div>

        <div class="card">
            <div class="card-header">
                <h3>Ressource</h3>
            </div>
            <p><strong>Nom :</strong> {{ $reservation->resource->name }}</p>
            <p><strong>Catégorie :</strong> {{ $reservation->resource->category->name }}</p>
            <p><strong>Prix :</strong> {{ $reservation->resource->price_per_hour }} €/h</p>
            <hr style="margin: 1rem 0; border: 0; border-top: 1px solid var(--border-color);">
            <p><strong>Coût estimé :</strong> 
                @php
                    $start = \Carbon\Carbon::parse($reservation->start_time);
                    $end = \Carbon\Carbon::parse($reservation->end_time);
                    $hours = $start->diffInHours($end);
                    $cost = $hours * $reservation->resource->price_per_hour;
                @endphp
                {{ number_format($cost, 2) }} €
            </p>
        </div>
    </div>
</x-app-layout>
