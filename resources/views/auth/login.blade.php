<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="alert alert-success" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="card" style="max-width: 400px; margin: 2rem auto;">
        @csrf
        <div style="text-align: center; margin-bottom: 2rem;">
            <h2 style="color: var(--primary-color);">Connexion</h2>
        </div>

        <!-- Email Address -->
        <div class="form-group">
            <label for="email" class="form-label">Email</label>
            <input id="email" class="form-control" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="alert alert-error" style="margin-top: 0.5rem; padding: 0.5rem;" />
        </div>

        <!-- Password -->
        <div class="form-group">
            <label for="password" class="form-label">Mot de passe</label>
            <input id="password" class="form-control" type="password" name="password" required autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="alert alert-error" style="margin-top: 0.5rem; padding: 0.5rem;" />
        </div>

        <!-- Remember Me -->
        <div class="form-group" style="display: flex; align-items: center;">
            <input id="remember_me" type="checkbox" name="remember" style="margin-right: 0.5rem;">
            <label for="remember_me" style="font-size: 0.9rem; color: var(--secondary-color);">Se souvenir de moi</label>
        </div>

        <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 2rem;">
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" style="color: var(--secondary-color); font-size: 0.9rem; text-decoration: none;">
                    Mot de passe oublié ?
                </a>
            @endif

            <button type="submit" class="btn btn-primary">
                Se connecter
            </button>
        </div>
        
        <div style="text-align: center; margin-top: 1.5rem; border-top: 1px solid var(--border-color); padding-top: 1rem;">
            <span style="font-size: 0.9rem;">Pas encore de compte ?</span>
            <a href="{{ route('register') }}" style="color: var(--primary-color); text-decoration: none; font-weight: 500;">S'inscrire</a>
        </div>
    </form>
</x-guest-layout>
