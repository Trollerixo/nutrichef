<x-guest-layout>
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-sm">
                <div class="card-body p-5">
                    <h2 class="fw-bold mb-1">Recupera tu acceso</h2>
                    <p class="text-muted mb-4">
                        Te enviaremos un enlace para restablecer tu contraseña.
                    </p>

                    <x-auth-session-status :status="session('status')" class="mb-3" />

                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf

                        <div class="mb-4">
                            <x-input-label for="email" value="Correo electrónico" />
                            <x-text-input id="email" type="email" name="email"
                                :value="old('email')" required autofocus />
                            <x-input-error :messages="$errors->get('email')" />
                        </div>

                        <div class="d-grid mb-3">
                            <x-primary-button>Enviar enlace</x-primary-button>
                        </div>
                    </form>

                    <p class="text-center mb-0">
                        <a href="{{ route('login') }}" class="text-muted small text-decoration-none">
                            ← Volver
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
