<x-app-layout>
    <x-slot name="header">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <span>Journal d'activité</span>
            <div>
                <a href="{{ route('admin.activity.export') }}" class="btn btn-secondary">Exporter CSV</a>
            </div>
        </div>
    </x-slot>

    <!-- Filters -->
    <div class="card">
        <form action="{{ route('admin.activity.index') }}" method="GET" style="display: flex; gap: 1rem; flex-wrap: wrap; align-items: flex-end;">
            <div class="form-group" style="margin-bottom: 0;">
                <label for="user_id" style="font-size: 0.8rem; margin-bottom: 0.2rem;">Utilisateur</label>
                <select name="user_id" id="user_id" class="form-control" style="width: 200px;">
                    <option value="">Tous les utilisateurs</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group" style="margin-bottom: 0;">
                <label for="action" style="font-size: 0.8rem; margin-bottom: 0.2rem;">Action</label>
                <input type="text" name="action" id="action" class="form-control" placeholder="ex: created, updated" value="{{ request('action') }}" style="width: 150px;">
            </div>

            <div class="form-group" style="margin-bottom: 0;">
                <label for="date_from" style="font-size: 0.8rem; margin-bottom: 0.2rem;">De</label>
                <input type="date" name="date_from" id="date_from" class="form-control" value="{{ request('date_from') }}">
            </div>

            <div class="form-group" style="margin-bottom: 0;">
                <label for="date_to" style="font-size: 0.8rem; margin-bottom: 0.2rem;">À</label>
                <input type="date" name="date_to" id="date_to" class="form-control" value="{{ request('date_to') }}">
            </div>

            <div style="display: flex; gap: 0.5rem;">
                <button type="submit" class="btn btn-primary">Filtrer</button>
                @if(request()->anyFilled(['user_id', 'action', 'date_from', 'date_to']))
                    <a href="{{ route('admin.activity.index') }}" class="btn btn-secondary">Réinitialiser</a>
                @endif
            </div>
        </form>
    </div>

    <!-- Logs Table -->
    <div class="card">
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Utilisateur</th>
                        <th>Action</th>
                        <th>Description</th>
                        <th>IP Address</th>
                        <th>Détails</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                        <tr>
                            <td style="white-space: nowrap;">
                                {{ $log->created_at->format('d/m/Y H:i:s') }}
                            </td>
                            <td>
                                @if($log->user)
                                    <a href="{{ route('admin.activity.user', $log->user->id) }}" style="color: var(--primary-color);">
                                        {{ $log->user->name }}
                                    </a>
                                @else
                                    <span style="color: var(--secondary-color);">Système / Supprimé</span>
                                @endif
                            </td>
                            <td>
                                <span class="badge badge-info">{{ $log->action }}</span>
                            </td>
                            <td>{{ $log->description }}</td>
                            <td><small>{{ $log->ip_address }}</small></td>
                            <td>
                                <a href="{{ route('admin.activity.show', $log) }}" class="btn btn-secondary" style="padding: 0.25rem 0.5rem; font-size: 0.8rem;">Voir</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 2rem; color: var(--secondary-color);">
                                Aucun journal d'activité trouvé.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div style="margin-top: 1rem;">
            {{ $logs->links() }}
        </div>
    </div>
</x-app-layout>
