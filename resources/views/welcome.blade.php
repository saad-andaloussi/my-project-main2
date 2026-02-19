<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'Data Center Manager') }}</title>
        <!-- Custom CSS -->
        <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    </head>
    <body style="background: linear-gradient(135deg, #0056b3 0%, #004494 100%); min-height: 100vh; display: flex; align-items: center; justify-content: center;">
        
        <div style="background: white; padding: 3rem; border-radius: 8px; box-shadow: 0 10px 25px rgba(0,0,0,0.2); max-width: 600px; width: 90%; text-align: center;">
            <h1 style="color: var(--primary-color); font-size: 2.5rem; margin-bottom: 1rem;">Data Center Manager</h1>
            <p style="color: var(--secondary-color); font-size: 1.1rem; margin-bottom: 2rem;">
                Gestion centralisée des ressources informatiques : Serveurs, Stockage, Réseaux et Machines Virtuelles.
            </p>

            <div style="display: flex; gap: 1rem; justify-content: center; margin-bottom: 2rem;">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="btn btn-primary" style="padding: 0.75rem 2rem; font-size: 1.1rem;">Accéder au Tableau de Bord</a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-primary" style="padding: 0.75rem 2rem; font-size: 1.1rem;">Connexion</a>

                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn btn-secondary" style="padding: 0.75rem 2rem; font-size: 1.1rem;">Inscription</a>
                        @endif
                    @endauth
                @endif
            </div>

            <div class="grid-3" style="margin-top: 3rem; text-align: left;">
                <div>
                    <h4 style="color: var(--primary-color); margin-bottom: 0.5rem;">Ressources</h4>
                    <p style="font-size: 0.9rem; color: #666;">Catalogue complet et disponibilité en temps réel.</p>
                </div>
                <div>
                    <h4 style="color: var(--primary-color); margin-bottom: 0.5rem;">Réservations</h4>
                    <p style="font-size: 0.9rem; color: #666;">Processus simple de demande et d'approbation.</p>
                </div>
                <div>
                    <h4 style="color: var(--primary-color); margin-bottom: 0.5rem;">Suivi</h4>
                    <p style="font-size: 0.9rem; color: #666;">Tableaux de bord et statistiques détaillés.</p>
                </div>
            </div>
        </div>

    </body>
</html>
