<x-app-layout>
    <x-slot name="header">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <span>Détails de la ressource : {{ $resource->name }}</span>
            <a href="{{ route('resources.index') }}" class="btn btn-secondary">Retour au catalogue</a>
        </div>
    </x-slot>

    <div class="grid-3">
        <div class="card" style="grid-column: span 2;">
            <div class="card-header">
                <h3>Informations Générales</h3>
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
            
            <div style="margin-bottom: 2rem;">
                <p><strong>Description:</strong></p>
                <p>{{ $resource->description }}</p>
            </div>

            <div class="grid-2">
                <div>
                    <p><strong>Catégorie:</strong> {{ $resource->category->name }}</p>
                    <p><strong>Série:</strong> {{ $resource->serial_number }}</p>
                    <p><strong>Prix par heure:</strong> {{ $resource->price_per_hour }} €</p>
                </div>
                <div>
                    <p><strong>CPU Cores:</strong> {{ $resource->cpu_cores ?? 'N/A' }}</p>
                    <p><strong>RAM:</strong> {{ $resource->ram_gb ? $resource->ram_gb . ' GB' : 'N/A' }}</p>
                    <p><strong>Stockage:</strong> {{ $resource->storage_gb ? $resource->storage_gb . ' GB' : 'N/A' }}</p>
                </div>
            </div>

            @auth
                <div style="margin-top: 2rem; padding-top: 1rem; border-top: 1px solid var(--border-color);">
                    @if($resource->status === 'available')
                        <a href="{{ route('reservations.create', ['resource_id' => $resource->id]) }}" class="btn btn-primary">Réserver cette ressource</a>
                    @else
                        <button class="btn btn-secondary" disabled>Non disponible pour réservation</button>
                    @endif

                    @if(auth()->user()->role === 'admin' || auth()->user()->role === 'technical_manager')
                        <a href="{{ route('resources.edit', $resource) }}" class="btn btn-warning" style="margin-left: 1rem;">Modifier</a>
                    @endif
                </div>
            @endauth
        </div>

        <div style="display: flex; flex-direction: column; gap: 1rem;">
            <div class="card">
                <div class="card-header">
                    <h3>Statistiques</h3>
                </div>
                <p><strong>Taux d'utilisation:</strong> {{ number_format($utilizationRate ?? 0, 1) }}%</p>
            </div>

            <div class="card">
                <div class="card-header">
                    <h3>Réservations Actives</h3>
                </div>
                @if(isset($activeReservations) && $activeReservations->count() > 0)
                    <ul style="list-style: none;">
                        @foreach($activeReservations as $reservation)
                            <li style="padding: 0.5rem 0; border-bottom: 1px solid var(--border-color);">
                                <small>{{ $reservation->start_time }} - {{ $reservation->end_time }}</small><br>
                                <strong>{{ $reservation->user->name }}</strong>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-secondary">Aucune réservation active.</p>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
