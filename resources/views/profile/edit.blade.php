<x-app-layout>
    <x-slot name="header">
        <h2>{{ __('Mon Profil') }}</h2>
    </x-slot>

    <div style="display: flex; flex-direction: column; gap: 2rem;">
        <div class="card">
            <div class="card-header">
                <h3>Informations du profil</h3>
                <p style="color: var(--secondary-color); font-size: 0.9rem;">Mettre à jour vos informations de compte et votre adresse email.</p>
            </div>
            <div style="max-width: 600px;">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3>Modifier le mot de passe</h3>
                <p style="color: var(--secondary-color); font-size: 0.9rem;">Assurez-vous d'utiliser un mot de passe long et aléatoire pour rester en sécurité.</p>
            </div>
            <div style="max-width: 600px;">
                @include('profile.partials.update-password-form')
            </div>
        </div>

        <div class="card" style="border: 1px solid var(--danger-color);">
            <div class="card-header" style="border-bottom-color: var(--danger-color);">
                <h3 style="color: var(--danger-color);">Supprimer le compte</h3>
                <p style="color: var(--secondary-color); font-size: 0.9rem;">Une fois votre compte supprimé, toutes ses ressources et données seront définitivement effacées.</p>
            </div>
            <div style="max-width: 600px;">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</x-app-layout>
