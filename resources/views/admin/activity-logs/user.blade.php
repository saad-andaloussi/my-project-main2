<x-app-layout>
    <x-slot name="header">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <span>Activité de l'utilisateur : {{ $user->name }}</span>
            <a href="{{ route('admin.activity.index') }}" class="btn btn-secondary">Retour au journal</a>
        </div>
    </x-slot>

    <div class="card">
        <div style="display: flex; gap: 2rem; margin-bottom: 2rem; padding-bottom: 1rem; border-bottom: 1px solid var(--border-color);">
            <div>
                <strong>Email :</strong> {{ $user->email }}
            </div>
            <div>
                <strong>Rôle :</strong> {{ $user->role->name ?? 'N/A' }}
            </div>
            <div>
                <strong>Inscrit le :</strong> {{ $user->created_at->format('d/m/Y') }}
            </div>
        </div>

        <h3>Dernières activités (100)</h3>
        
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Action</th>
                        <th>Description</th>
                        <th>IP</th>
                        <th>Détails</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                        <tr>
                            <td>{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
                            <td><span class="badge badge-info">{{ $log->action }}</span></td>
                            <td>{{ $log->description }}</td>
                            <td><small>{{ $log->ip_address }}</small></td>
                            <td>
                                <a href="{{ route('admin.activity.show', $log) }}" class="btn btn-secondary" style="padding: 0.25rem 0.5rem; font-size: 0.8rem;">Voir</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 2rem; color: var(--secondary-color);">
                                Aucune activité enregistrée pour cet utilisateur.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
