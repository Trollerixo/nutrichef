<h4 class="fw-semibold mb-4">Seguridad</h4>

<form method="POST" action="{{ route('password.update') }}">
    @csrf
    @method('PUT')

    <div class="row g-3 mb-3">
        <div class="col-md-6">
            <x-input-label for="update_password_current_password" value="Contraseña actual" />
            <x-text-input id="update_password_current_password" name="current_password"
                type="password" autocomplete="current-password" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" />
        </div>
    </div>

    <div class="row g-3 mb-3">
        <div class="col-md-6">
            <x-input-label for="update_password_password" value="Nueva contraseña" />
            <x-text-input id="update_password_password" name="password"
                type="password" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password')" />
        </div>
        <div class="col-md-6">
            <x-input-label for="update_password_password_confirmation" value="Confirmar" />
            <x-text-input id="update_password_password_confirmation"
                name="password_confirmation" type="password" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" />
        </div>
    </div>

    @if (session('status') === 'password-updated')
        <div class="alert alert-success mb-3">Contraseña actualizada correctamente.</div>
    @endif

    <x-primary-button>Actualizar contraseña</x-primary-button>
</form>
