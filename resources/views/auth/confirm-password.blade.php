<x-guest-layout>
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-sm">
                <div class="card-body p-5">
                    <h2 class="fw-bold mb-3">Confirma tu contraseña</h2>
                    <p class="text-muted mb-4">
                        Por seguridad, confirma tu contraseña antes de continuar.
                    </p>

                    <form method="POST" action="{{ route('password.confirm') }}">
                        @csrf

                        <div class="mb-4">
                            <x-input-label for="password" value="Contraseña" />
                            <x-text-input id="password" type="password" name="password"
                                required autocomplete="current-password" />
                            <x-input-error :messages="$errors->get('password')" />
                        </div>

                        <div class="d-grid">
                            <x-primary-button>Confirmar</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
