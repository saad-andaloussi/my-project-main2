<section>
    <form method="post" action="{{ route('password.update') }}">
        @csrf
        @method('put')

        <div class="form-group">
            <label for="update_password_current_password" class="form-label">{{ __('Mot de passe actuel') }}</label>
            <input id="update_password_current_password" name="current_password" type="password" class="form-control" autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="alert alert-error" style="margin-top: 0.5rem;" />
        </div>

        <div class="form-group">
            <label for="update_password_password" class="form-label">{{ __('Nouveau mot de passe') }}</label>
            <input id="update_password_password" name="password" type="password" class="form-control" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="alert alert-error" style="margin-top: 0.5rem;" />
        </div>

        <div class="form-group">
            <label for="update_password_password_confirmation" class="form-label">{{ __('Confirmer le mot de passe') }}</label>
            <input id="update_password_password_confirmation" name="password_confirmation" type="password" class="form-control" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="alert alert-error" style="margin-top: 0.5rem;" />
        </div>

        <div style="display: flex; align-items: center; gap: 1rem;">
            <button type="submit" class="btn btn-primary">{{ __('Enregistrer') }}</button>

            @if (session('status') === 'password-updated')
                <p style="color: var(--success-color); font-size: 0.9rem;">{{ __('Enregistré.') }}</p>
            @endif
        </div>
    </form>
</section>
