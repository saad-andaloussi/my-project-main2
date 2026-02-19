<x-app-layout>
    <x-slot name="header">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <span>Incidents</span>
            <a href="{{ route('incidents.create') }}" class="btn btn-danger">Signaler un incident</a>
        </div>
    </x-slot>

    <div class="card">
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Titre</th>
                        <th>Ressource</th>
                        <th>Gravité</th>
                        <th>État</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($incidents as $incident)
                        <tr>
                            <td>
                                <a href="{{ route('incidents.show', $incident) }}" style="font-weight: bold; color: var(--text-color); text-decoration: none;">
                                    {{ $incident->title }}
                                </a>
                            </td>
                            <td>
                                @if($incident->resource)
                                    <a href="{{ route('resources.show', $incident->resource) }}" style="color: var(--primary-color);">
                                        {{ $incident->resource->name }}
                                    </a>
                                @else
                                    <span style="color: var(--secondary-color);">Général</span>
                                @endif
                            </td>
                            <td>
                                @php
                                    $severityClass = match($incident->severity) {
                                        'low' => 'badge-info',
                                        'medium' => 'badge-warning',
                                        'high' => 'badge-danger',
                                        'critical' => 'badge-danger',
                                        default => 'badge-secondary'
                                    };
                                    $severityLabel = match($incident->severity) {
                                        'low' => 'Basse',
                                        'medium' => 'Moyenne',
                                        'high' => 'Haute',
                                        'critical' => 'Critique',
                                        default => $incident->severity
                                    };
                                @endphp
                                <span class="badge {{ $severityClass }}">{{ $severityLabel }}</span>
                            </td>
                            <td>
                                @php
                                    $statusLabel = match($incident->status) {
                                        'open' => 'Ouvert',
                                        'in_progress' => 'En cours',
                                        'resolved' => 'Résolu',
                                        'closed' => 'Fermé',
                                        default => $incident->status
                                    };
                                @endphp
                                <span style="font-size: 0.9rem; {{ $incident->status === 'closed' ? 'color: var(--secondary-color);' : '' }}">
                                    {{ $statusLabel }}
                                </span>
                            </td>
                            <td>{{ $incident->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <a href="{{ route('incidents.show', $incident) }}" class="btn btn-secondary" style="padding: 0.25rem 0.5rem; font-size: 0.8rem;">Voir</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 2rem; color: var(--secondary-color);">
                                Aucun incident signalé.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div style="margin-top: 1rem;">
            {{ $incidents->links() }}
        </div>
    </div>
</x-app-layout>
