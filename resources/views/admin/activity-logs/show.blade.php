<x-app-layout>
    <x-slot name="header">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <span>Détail de l'activité #{{ $log->id }}</span>
            <a href="{{ route('admin.activity.index') }}" class="btn btn-secondary">Retour</a>
        </div>
    </x-slot>

    <div class="card" style="max-width: 800px; margin: 0 auto;">
        <div style="margin-bottom: 1.5rem; border-bottom: 1px solid var(--border-color); padding-bottom: 1rem;">
            <h3>Informations Générales</h3>
        </div>

        <div class="grid-2">
            <div>
                <strong>Date :</strong>
                <p>{{ $log->created_at->format('d/m/Y H:i:s') }}</p>
            </div>
            <div>
                <strong>Action :</strong>
                <p><span class="badge badge-info">{{ $log->action }}</span></p>
            </div>
            <div>
                <strong>Utilisateur :</strong>
                <p>
                    @if($log->user)
                        <a href="{{ route('admin.activity.user', $log->user->id) }}" style="color: var(--primary-color);">
                            {{ $log->user->name }}
                        </a>
                        <br><small>{{ $log->user->email }}</small>
                    @else
                        Système / Utilisateur supprimé
                    @endif
                </p>
            </div>
            <div>
                <strong>Adresse IP :</strong>
                <p>{{ $log->ip_address ?? 'N/A' }}</p>
            </div>
            <div>
                <strong>User Agent :</strong>
                <p style="font-size: 0.8rem; color: var(--secondary-color);">{{ $log->user_agent ?? 'N/A' }}</p>
            </div>
        </div>

        <div style="margin-top: 2rem;">
            <strong>Description :</strong>
            <p style="background-color: var(--bg-color); padding: 0.5rem; border-radius: 4px;">{{ $log->description }}</p>
        </div>

        <div style="margin-top: 2rem;">
            <strong>Cible (Modèle) :</strong>
            <p>
                Type: {{ $log->model_type }}<br>
                ID: {{ $log->model_id }}
            </p>
        </div>

        @if($log->properties)
            <div style="margin-top: 2rem;">
                <strong>Données (Propriétés) :</strong>
                <pre style="background-color: #f8f9fa; padding: 1rem; border-radius: 4px; overflow-x: auto; font-size: 0.9rem;">
@json($log->properties, JSON_PRETTY_PRINT)
                </pre>
            </div>
        @endif
    </div>
</x-app-layout>
