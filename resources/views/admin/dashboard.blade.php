<x-app-layout>
    <x-slot name="header">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <span>Tableau de Bord Administrateur</span>
            <span style="font-size: 0.9rem; color: var(--secondary-color);">{{ now()->format('d/m/Y') }}</span>
        </div>
    </x-slot>

    <!-- Statistics Grid -->
    <div class="grid-4" style="margin-bottom: 2rem;">
        <!-- Users Card -->
        <div class="card" style="border-left: 4px solid var(--primary-color);">
            <h4 style="color: var(--secondary-color); font-size: 0.9rem; margin-bottom: 0.5rem;">Utilisateurs</h4>
            <div style="font-size: 1.5rem; font-weight: bold; color: var(--text-color);">{{ $total_users }}</div>
            <div style="font-size: 0.8rem; margin-top: 0.5rem;">
                <span style="color: var(--success-color);">{{ $new_users_this_month }}</span> nouveaux ce mois
            </div>
        </div>

        <!-- Resources Card -->
        <div class="card" style="border-left: 4px solid var(--info-color);">
            <h4 style="color: var(--secondary-color); font-size: 0.9rem; margin-bottom: 0.5rem;">Ressources</h4>
            <div style="font-size: 1.5rem; font-weight: bold; color: var(--text-color);">{{ $total_resources }}</div>
            <div style="font-size: 0.8rem; margin-top: 0.5rem;">
                <span style="color: var(--warning-color);">{{ $in_use_resources }}</span> en cours d'utilisation
            </div>
        </div>

        <!-- Reservations Card -->
        <div class="card" style="border-left: 4px solid var(--success-color);">
            <h4 style="color: var(--secondary-color); font-size: 0.9rem; margin-bottom: 0.5rem;">Réservations</h4>
            <div style="font-size: 1.5rem; font-weight: bold; color: var(--text-color);">{{ $total_reservations }}</div>
            <div style="font-size: 0.8rem; margin-top: 0.5rem;">
                <span style="color: var(--warning-color);">{{ $pending_reservations }}</span> en attente
            </div>
        </div>

        <!-- Revenue Card -->
        <div class="card" style="border-left: 4px solid var(--warning-color);">
            <h4 style="color: var(--secondary-color); font-size: 0.9rem; margin-bottom: 0.5rem;">Revenus</h4>
            <div style="font-size: 1.5rem; font-weight: bold; color: var(--text-color);">{{ number_format($total_revenue, 2) }} €</div>
            <div style="font-size: 0.8rem; margin-top: 0.5rem;">
                <span style="color: var(--success-color);">+{{ number_format($revenue_this_month, 2) }} €</span> ce mois
            </div>
        </div>
    </div>

    <div class="grid-2">
        <!-- Resource Status Breakdown -->
        <div class="card">
            <div class="card-header">
                <h3>État des Ressources</h3>
            </div>
            <div style="display: flex; flex-direction: column; gap: 1rem;">
                <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.5rem; background-color: rgba(40, 167, 69, 0.1); border-radius: 4px;">
                    <span style="color: var(--success-color); font-weight: bold;">Disponibles</span>
                    <span class="badge badge-success">{{ $available_resources }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.5rem; background-color: rgba(255, 193, 7, 0.1); border-radius: 4px;">
                    <span style="color: var(--warning-color); font-weight: bold;">En utilisation</span>
                    <span class="badge badge-warning">{{ $in_use_resources }}</span>
                </div>
                <div style="display: flex; justify-content: space-between; align-items: center; padding: 0.5rem; background-color: rgba(220, 53, 69, 0.1); border-radius: 4px;">
                    <span style="color: var(--danger-color); font-weight: bold;">Maintenance</span>
                    <span class="badge badge-danger">{{ $maintenance_resources }}</span>
                </div>
            </div>
        </div>

        <!-- Incidents Overview -->
        <div class="card">
            <div class="card-header">
                <h3>Incidents</h3>
                <a href="{{ route('incidents.index') }}" class="btn btn-secondary btn-sm">Voir tout</a>
            </div>
            <div style="text-align: center; padding: 1rem;">
                @if($open_incidents > 0)
                    <div style="font-size: 3rem; font-weight: bold; color: var(--danger-color);">{{ $open_incidents }}</div>
                    <div style="color: var(--secondary-color);">Incidents ouverts</div>
                    @if($critical_incidents > 0)
                        <div style="margin-top: 0.5rem; color: darkred; font-weight: bold;">
                            dont {{ $critical_incidents }} critiques
                        </div>
                    @endif
                @else
                    <div style="font-size: 3rem; font-weight: bold; color: var(--success-color);">0</div>
                    <div style="color: var(--secondary-color);">Aucun incident ouvert</div>
                @endif
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="card" style="margin-top: 2rem;">
        <div class="card-header">
            <h3>Actions Rapides</h3>
        </div>
        <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
            <a href="{{ route('admin.reservations') }}" class="btn btn-primary">Gérer les réservations</a>
            <a href="{{ route('resources.create') }}" class="btn btn-secondary">Ajouter une ressource</a>
            <a href="{{ route('categories.index') }}" class="btn btn-secondary">Gérer les catégories</a>
            <a href="{{ route('admin.activity.index') }}" class="btn btn-secondary">Journal d'activité</a>
        </div>
    </div>
</x-app-layout>
