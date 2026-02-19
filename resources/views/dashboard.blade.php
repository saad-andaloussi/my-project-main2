<x-app-layout>
    <x-slot name="header">
        Tableau de bord
    </x-slot>

    <div class="grid-3">
        <!-- Stat Cards -->
        <div class="card" style="border-left: 4px solid var(--warning-color);">
            <h3>En attente</h3>
            <p style="font-size: 2rem; font-weight: bold; color: var(--warning-color);">{{ $pending_reservations }}</p>
            <p style="color: var(--secondary-color);">Demandes de réservation</p>
        </div>

        <div class="card" style="border-left: 4px solid var(--success-color);">
            <h3>Approuvées</h3>
            <p style="font-size: 2rem; font-weight: bold; color: var(--success-color);">{{ $approved_reservations }}</p>
            <p style="color: var(--secondary-color);">Réservations validées</p>
        </div>

        <div class="card" style="border-left: 4px solid var(--info-color);">
            <h3>Actives</h3>
            <p style="font-size: 2rem; font-weight: bold; color: var(--info-color);">{{ $active_reservations }}</p>
            <p style="color: var(--secondary-color);">Réservations en cours</p>
        </div>
    </div>

    <div class="grid-2">
        <!-- Recent Activity -->
        <div class="card">
            <div class="card-header">
                <h3>Activité Récente</h3>
                <a href="{{ route('reservations.index') }}" class="btn btn-secondary" style="font-size: 0.8rem;">Voir tout</a>
            </div>
            @if($recent_reservations->count() > 0)
                <ul style="list-style: none;">
                    @foreach($recent_reservations as $reservation)
                        <li style="padding: 1rem 0; border-bottom: 1px solid var(--border-color); display: flex; justify-content: space-between; align-items: center;">
                            <div>
                                <strong>{{ $reservation->resource->name }}</strong>
                                <br>
                                <small style="color: var(--secondary-color);">{{ $reservation->created_at->diffForHumans() }}</small>
                            </div>
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
                        </li>
                    @endforeach
                </ul>
            @else
                <p style="padding: 1rem; text-align: center; color: var(--secondary-color);">Aucune activité récente.</p>
            @endif
        </div>

        <!-- Quick Actions -->
        <div class="card">
            <div class="card-header">
                <h3>Actions Rapides</h3>
            </div>
            <div style="display: flex; flex-direction: column; gap: 1rem;">
                <a href="{{ route('reservations.create') }}" class="btn btn-primary" style="text-align: center;">
                    Nouvelle Réservation
                </a>
                <a href="{{ route('resources.index') }}" class="btn btn-secondary" style="text-align: center;">
                    Parcourir le catalogue
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
