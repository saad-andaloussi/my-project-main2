<x-app-layout>
    <x-slot name="header">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('manager'))
                <span>Gestion des Réservations</span>
            @else
                <span>Mes Réservations</span>
            @endif
            
            <a href="{{ route('reservations.create') }}" class="btn btn-primary">Nouvelle réservation</a>
        </div>
    </x-slot>

    <div class="card">
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Ressource</th>
                        @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('manager'))
                            <th>Utilisateur</th>
                        @endif
                        <th>Début</th>
                        <th>Fin</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reservations as $reservation)
                        <tr>
                            <td>
                                <strong>{{ $reservation->resource->name }}</strong><br>
                                <small style="color: var(--secondary-color);">{{ $reservation->resource->category->name }}</small>
                            </td>
                            
                            @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('manager'))
                                <td>
                                    {{ $reservation->user->name }}<br>
                                    <small style="color: var(--secondary-color);">{{ $reservation->user->email }}</small>
                                </td>
                            @endif
                            
                            <td>{{ \Carbon\Carbon::parse($reservation->start_time)->format('d/m/Y H:i') }}</td>
                            <td>{{ \Carbon\Carbon::parse($reservation->end_time)->format('d/m/Y H:i') }}</td>
                            <td>
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
                            </td>
                            <td>
                                <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                                    <a href="{{ route('reservations.show', $reservation) }}" class="btn btn-secondary" style="padding: 0.25rem 0.5rem; font-size: 0.8rem;">Voir</a>
                                    
                                    @if(auth()->user()->hasRole('admin') && $reservation->status === 'pending')
                                        <form action="{{ route('admin.approve-reservation', $reservation) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-success" style="padding: 0.25rem 0.5rem; font-size: 0.8rem;">Accepter</button>
                                        </form>
                                        <form action="{{ route('admin.decline-reservation', $reservation) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-danger" style="padding: 0.25rem 0.5rem; font-size: 0.8rem;">Refuser</button>
                                        </form>
                                    @endif

                                    @if(auth()->id() === $reservation->user_id && $reservation->status === 'pending')
                                        <a href="{{ route('reservations.edit', $reservation) }}" class="btn btn-warning" style="padding: 0.25rem 0.5rem; font-size: 0.8rem;">Modifier</a>
                                        <form action="{{ route('reservations.destroy', $reservation) }}" method="POST" class="delete-form">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger delete-confirm" style="padding: 0.25rem 0.5rem; font-size: 0.8rem;">Annuler</button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ (auth()->user()->role === 'admin' || auth()->user()->role === 'manager') ? 6 : 5 }}" style="text-align: center; padding: 2rem; color: var(--secondary-color);">
                                Aucune réservation trouvée.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div style="margin-top: 1rem;">
            {{ $reservations->links() }}
        </div>
    </div>
</x-app-layout>
