<x-app-layout>
    <x-slot name="header">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <span>Incident : {{ $incident->title }}</span>
            <div style="display: flex; gap: 0.5rem;">
                <a href="{{ route('incidents.index') }}" class="btn btn-secondary">Retour</a>
            </div>
        </div>
    </x-slot>

    <div class="grid-2">
        <!-- Details Column -->
        <div class="card">
            <div class="card-header">
                <h3>Détails de l'incident</h3>
            </div>
            
            <div style="margin-bottom: 1.5rem;">
                <strong>Statut :</strong>
                @php
                    $statusLabel = match($incident->status) {
                        'open' => 'Ouvert',
                        'in_progress' => 'En cours',
                        'resolved' => 'Résolu',
                        'closed' => 'Fermé',
                        default => $incident->status
                    };
                    $statusColor = match($incident->status) {
                        'open' => 'var(--danger-color)',
                        'in_progress' => 'var(--warning-color)',
                        'resolved' => 'var(--success-color)',
                        'closed' => 'var(--secondary-color)',
                        default => 'var(--text-color)'
                    };
                @endphp
                <span style="font-weight: bold; color: {{ $statusColor }};">{{ $statusLabel }}</span>
            </div>

            <div style="margin-bottom: 1.5rem;">
                <strong>Gravité :</strong>
                @php
                    $severityLabel = match($incident->severity) {
                        'low' => 'Basse',
                        'medium' => 'Moyenne',
                        'high' => 'Haute',
                        'critical' => 'Critique',
                        default => $incident->severity
                    };
                    $severityColor = match($incident->severity) {
                        'low' => 'var(--info-color)',
                        'medium' => 'var(--warning-color)',
                        'high' => 'var(--danger-color)',
                        'critical' => 'darkred',
                        default => 'var(--text-color)'
                    };
                @endphp
                <span style="font-weight: bold; color: {{ $severityColor }};">{{ $severityLabel }}</span>
            </div>

            <div style="margin-bottom: 1.5rem;">
                <strong>Ressource concernée :</strong>
                @if($incident->resource)
                    <a href="{{ route('resources.show', $incident->resource) }}" style="color: var(--primary-color);">
                        {{ $incident->resource->name }}
                    </a>
                @else
                    <span style="color: var(--secondary-color);">Non spécifié / Problème général</span>
                @endif
            </div>

            <div style="margin-bottom: 1.5rem;">
                <strong>Signalé par :</strong>
                <span>{{ $incident->user->name }}</span>
                <span style="color: var(--secondary-color); font-size: 0.9rem;">({{ $incident->created_at->format('d/m/Y H:i') }})</span>
            </div>

            <div style="margin-top: 2rem;">
                <strong>Description :</strong>
                <div style="background-color: var(--bg-color); padding: 1rem; border-radius: 4px; margin-top: 0.5rem; white-space: pre-wrap;">{{ $incident->description }}</div>
            </div>
        </div>

        <!-- Actions / Resolution Column -->
        <div class="card">
            <div class="card-header">
                <h3>Gestion et Résolution</h3>
            </div>

            @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('technical_manager'))
                <div style="margin-bottom: 2rem; border-bottom: 1px solid var(--border-color); padding-bottom: 2rem;">
                    <h4>Actions Administratives</h4>
                    
                    <div style="display: flex; gap: 1rem; margin-top: 1rem; flex-wrap: wrap;">
                        @if($incident->status === 'open')
                            <form action="{{ route('incidents.resolve', $incident) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-success">Marquer comme Résolu</button>
                            </form>
                        @endif

                        @if($incident->status !== 'closed')
                            <form action="{{ route('incidents.close', $incident) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-secondary">Fermer l'incident</button>
                            </form>
                        @endif
                    </div>
                </div>
            @endif

            <div>
                <h4>Historique / Commentaires</h4>
                <p style="color: var(--secondary-color); font-style: italic;">
                    (Fonctionnalité de commentaires à venir)
                </p>
            </div>
        </div>
    </div>
</x-app-layout>
