<x-guest-layout>
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-sm">
                <div class="card-body p-5">
                    <h2 class="fw-bold mb-1">Crea tu cuenta</h2>
                    <p class="text-muted mb-4">Personaliza tus recomendaciones y guarda tus favoritas.</p>

                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="mb-3">
                            <x-input-label for="name" value="Nombre completo" />
                            <x-text-input id="name" type="text" name="name"
                                :value="old('name')" required autofocus autocomplete="name" />
                            <x-input-error :messages="$errors->get('name')" />
                        </div>

                        <div class="mb-3">
                            <x-input-label for="email" value="Correo electrónico" />
                            <x-text-input id="email" type="email" name="email"
                                :value="old('email')" required autocomplete="username" />
                            <x-input-error :messages="$errors->get('email')" />
                        </div>

                        <div class="mb-3">
                            <x-input-label for="password" value="Contraseña" />
                            <x-text-input id="password" type="password" name="password"
                                required autocomplete="new-password" />
                            <div class="form-text">Mínimo 8 caracteres.</div>
                            <x-input-error :messages="$errors->get('password')" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="password_confirmation" value="Confirma la contraseña" />
                            <x-text-input id="password_confirmation" type="password"
                                name="password_confirmation" required autocomplete="new-password" />
                            <x-input-error :messages="$errors->get('password_confirmation')" />
                        </div>

                        <div class="d-grid mb-3">
                            <x-primary-button>Registrarme</x-primary-button>
                        </div>
                    </form>

                    <p class="text-center text-muted small mb-0">
                        ¿Ya tienes cuenta?
                        <a href="{{ route('login') }}" class="text-decoration-none">Inicia sesión</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
