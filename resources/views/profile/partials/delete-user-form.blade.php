<section>
    <button id="delete-account-btn" class="btn btn-danger">
        {{ __('Supprimer le compte') }}
    </button>

    <div id="delete-account-modal" style="display: {{ $errors->userDeletion->isNotEmpty() ? 'block' : 'none' }}; margin-top: 2rem; padding: 2rem; border: 1px solid var(--border-color); border-radius: 8px; background-color: #fff0f0;">
        <form method="post" action="{{ route('profile.destroy') }}">
            @csrf
            @method('delete')

            <h3 style="color: var(--danger-color); margin-bottom: 1rem;">
                {{ __('Êtes-vous sûr de vouloir supprimer votre compte ?') }}
            </h3>

            <p style="color: var(--secondary-color); margin-bottom: 1.5rem;">
                {{ __('Une fois votre compte supprimé, toutes ses ressources et données seront définitivement effacées. Veuillez entrer votre mot de passe pour confirmer.') }}
            </p>

            <div class="form-group">
                <label for="password" class="form-label sr-only">{{ __('Mot de passe') }}</label>
                <input id="password" name="password" type="password" class="form-control" placeholder="{{ __('Mot de passe') }}" />
                <x-input-error :messages="$errors->userDeletion->get('password')" class="alert alert-error" style="margin-top: 0.5rem;" />
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 1rem;">
                <button type="button" class="btn btn-secondary" onclick="document.getElementById('delete-account-modal').style.display = 'none';">
                    {{ __('Annuler') }}
                </button>

                <button type="submit" class="btn btn-danger">
                    {{ __('Supprimer le compte') }}
                </button>
            </div>
        </form>
    </div>

    <script>
        document.getElementById('delete-account-btn').addEventListener('click', function() {
            document.getElementById('delete-account-modal').style.display = 'block';
        });
    </script>
</section>
