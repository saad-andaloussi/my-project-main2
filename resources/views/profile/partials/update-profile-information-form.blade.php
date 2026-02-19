<section>
    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}">
        @csrf
        @method('patch')

        <div class="form-group">
            <label for="name" class="form-label">Nom</label>
            <input id="name" name="name" type="text" class="form-control" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name" />
            <x-input-error class="alert alert-error" style="margin-top: 0.5rem;" :messages="$errors->get('name')" />
        </div>

        <div class="form-group">
            <label for="email" class="form-label">Email</label>
            <input id="email" name="email" type="email" class="form-control" value="{{ old('email', $user->email) }}" required autocomplete="username" />
            <x-input-error class="alert alert-error" style="margin-top: 0.5rem;" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div style="margin-top: 1rem;">
                    <p style="font-size: 0.9rem;">
                        {{ __('Votre adresse email n\'est pas vérifiée.') }}

                        <button form="send-verification" style="background: none; border: none; color: var(--primary-color); text-decoration: underline; cursor: pointer; font-size: 0.9rem;">
                            {{ __('Cliquez ici pour renvoyer l\'email de vérification.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p style="color: var(--success-color); font-size: 0.9rem; margin-top: 0.5rem;">
                            {{ __('Un nouveau lien de vérification a été envoyé à votre adresse email.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div style="display: flex; align-items: center; gap: 1rem;">
            <button type="submit" class="btn btn-primary">{{ __('Enregistrer') }}</button>

            @if (session('status') === 'profile-updated')
                <p style="color: var(--success-color); font-size: 0.9rem;">{{ __('Enregistré.') }}</p>
            @endif
        </div>
    </form>
</section>
