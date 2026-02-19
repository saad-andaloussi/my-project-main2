<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Data Center Manager') }}</title>

    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
</head>
<body>
    <div class="app-container">
        <header>
            <a href="{{ route('dashboard') }}" class="logo">
                DataCenter Manager
            </a>
            
            <nav>
                <ul class="nav-menu">
                    <li><a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">Tableau de bord</a></li>
                    <li><a href="{{ route('resources.index') }}" class="nav-link {{ request()->routeIs('resources.*') ? 'active' : '' }}">Ressources</a></li>
                    <li><a href="{{ route('reservations.index') }}" class="nav-link {{ request()->routeIs('reservations.*') ? 'active' : '' }}">Réservations</a></li>
                    <li><a href="{{ route('incidents.index') }}" class="nav-link {{ request()->routeIs('incidents.*') ? 'active' : '' }}">Incidents</a></li>
                    
                    @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('technical_manager'))
                        <li>
                            <div class="dropdown">
                                <a href="#" class="nav-link dropdown-toggle {{ request()->routeIs('admin.*') || request()->routeIs('categories.*') ? 'active' : '' }}" style="display: flex; align-items: center; gap: 0.25rem;">
                                    Administration <span>▼</span>
                                </a>
                                <div class="dropdown-content">
                                    @if(auth()->user()->hasRole('admin'))
                                        <a href="{{ route('admin.dashboard') }}" class="dropdown-item">Tableau de bord Admin</a>
                                        <a href="{{ route('admin.activity.index') }}" class="dropdown-item">Journal d'activité</a>
                                    @endif
                                    <a href="{{ route('categories.index') }}" class="dropdown-item">Catégories</a>
                                    <a href="{{ route('resources.create') }}" class="dropdown-item">Ajouter Ressource</a>
                                </div>
                            </div>
                        </li>
                    @endif
                </ul>
            </nav>

            <div class="user-menu">
                <!-- Notifications Dropdown -->
                <div class="dropdown">
                    <button class="btn btn-secondary" style="background: none; color: var(--text-color); padding: 0.5rem;">
                        Notifications
                        @if(auth()->user()->unreadNotifications->count() > 0)
                            <span class="badge badge-danger" style="margin-left: 0.5rem; font-size: 0.7rem;">{{ auth()->user()->unreadNotifications->count() }}</span>
                        @endif
                    </button>
                    <div class="dropdown-content" style="width: 300px;">
                        @forelse(auth()->user()->unreadNotifications->take(5) as $notification)
                            <div style="padding: 0.5rem 1rem; border-bottom: 1px solid var(--border-color);">
                                <small style="display: block; color: var(--secondary-color);">{{ $notification->created_at->diffForHumans() }}</small>
                                <span>{{ $notification->data['message'] ?? 'Nouvelle notification' }}</span>
                            </div>
                        @empty
                            <div style="padding: 1rem; text-align: center; color: var(--secondary-color);">
                                Aucune nouvelle notification
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- User Dropdown -->
                <div class="dropdown">
                    <button class="btn btn-primary" style="display: flex; align-items: center; gap: 0.5rem;">
                        {{ Auth::user()->name }}
                        <span style="font-size: 0.8rem;">▼</span>
                    </button>
                    <div class="dropdown-content">
                        <a href="{{ route('profile.edit') }}" class="dropdown-item">Mon Profil</a>
                        
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="dropdown-item" style="width: 100%; text-align: left; border: none; background: none; cursor: pointer; font-family: inherit; font-size: 1rem;">
                                Déconnexion
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <!-- Page Heading -->
        @isset($header)
            <div style="background: white; padding: 1.5rem 2rem; border-bottom: 1px solid var(--border-color); margin-bottom: 2rem;">
                <h2 style="font-size: 1.5rem; color: var(--text-color);">
                    {{ $header }}
                </h2>
            </div>
        @endisset

        <main>
            <!-- Flash Messages -->
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-error">
                    {{ session('error') }}
                </div>
            @endif

            {{ $slot }}
        </main>
    </div>
    <script src="{{ asset('js/custom.js') }}"></script>
</body>
</html>
