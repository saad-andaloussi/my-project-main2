<x-guest-layout>
    <form method="POST" action="{{ route('register') }}" class="card" style="max-width: 500px; margin: 2rem auto;">
        @csrf
        <div style="text-align: center; margin-bottom: 2rem;">
            <h2 style="color: var(--primary-color);">Inscription</h2>
        </div>

        <!-- Name -->
        <div class="form-group">
            <label for="name" class="form-label">Nom complet</label>
            <input id="name" class="form-control" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="alert alert-error" style="margin-top: 0.5rem; padding: 0.5rem;" />
        </div>

        <!-- Email Address -->
        <div class="form-group">
            <label for="email" class="form-label">Email</label>
            <input id="email" class="form-control" type="email" name="email" value="{{ old('email') }}" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="alert alert-error" style="margin-top: 0.5rem; padding: 0.5rem;" />
        </div>

        <!-- Password -->
        <div class="form-group">
            <label for="password" class="form-label">Mot de passe</label>
            <input id="password" class="form-control" type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="alert alert-error" style="margin-top: 0.5rem; padding: 0.5rem;" />
        </div>

        <!-- Confirm Password -->
        <div class="form-group">
            <label for="password_confirmation" class="form-label">Confirmer le mot de passe</label>
            <input id="password_confirmation" class="form-control" type="password" name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="alert alert-error" style="margin-top: 0.5rem; padding: 0.5rem;" />
        </div>

        <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 2rem;">
            <a href="{{ route('login') }}" style="color: var(--secondary-color); font-size: 0.9rem; text-decoration: none;">
                Déjà inscrit ?
            </a>

            <button type="submit" class="btn btn-primary">
                S'inscrire
            </button>
        </div>
    </form>
</x-guest-layout>
