<x-guest-layout>
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-sm">
                <div class="card-body p-5">
                    <h2 class="fw-bold mb-1">Hola de nuevo</h2>
                    <p class="text-muted mb-4">Inicia sesión para ver tu menú y favoritas.</p>

                    <x-auth-session-status :status="session('status')" class="mb-3" />

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="mb-3">
                            <x-input-label for="email" value="Correo electrónico" />
                            <x-text-input id="email" type="email" name="email"
                                :value="old('email')" required autofocus autocomplete="username" />
                            <x-input-error :messages="$errors->get('email')" />
                        </div>

                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <x-input-label for="password" value="Contraseña" />
                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}"
                                       class="text-muted small text-decoration-none">¿Olvidaste?</a>
                                @endif
                            </div>
                            <x-text-input id="password" type="password" name="password"
                                required autocomplete="current-password" />
                            <x-input-error :messages="$errors->get('password')" />
                        </div>

                        <div class="d-grid mb-3">
                            <x-primary-button>Iniciar sesión</x-primary-button>
                        </div>
                    </form>

                    <div class="text-center">
                        <p class="text-muted small mb-1">
                            ¿Aún no tienes cuenta?
                            <a href="{{ route('register') }}" class="text-decoration-none">Regístrate</a>
                        </p>
                        <p class="text-muted small mb-0">
                            ¿Solo quieres mirar recetas?
                            <a href="/" class="text-decoration-none">Entra como invitado</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
