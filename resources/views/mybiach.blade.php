<x-app-layout>
    <x-slot name="header">
        My Biach
    </x-slot>

    <div class="card" style="max-width: 768px; margin: 0 auto;">
        <h1 style="font-size: 2rem; margin-bottom: 1rem;">Welcome to My Biach Page!</h1>
        <p style="margin-bottom: 1rem;">This is a custom page created to demonstrate routing in Laravel.</p>
        <p style="margin-bottom: 1rem;">Current user: {{ $users[1]->name ?? 'Utilisateur' }}</p>
        <a href="{{ route('dashboard') }}" class="btn btn-primary">Retour au tableau de bord</a>
    </div>
</x-app-layout>
