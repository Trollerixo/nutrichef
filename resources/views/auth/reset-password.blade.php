<x-guest-layout>
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-sm">
                <div class="card-body p-5">
                    <h2 class="fw-bold mb-4">Recupera tu acceso</h2>

                    <form method="POST" action="{{ route('password.store') }}">
                        @csrf
                        <input type="hidden" name="token" value="{{ $request->route('token') }}">

                        <div class="mb-3">
                            <x-input-label for="email" value="Correo electrónico" />
                            <x-text-input id="email" type="email" name="email"
                                :value="old('email', $request->email)" required />
                            <x-input-error :messages="$errors->get('email')" />
                        </div>

                        <div class="mb-3">
                            <x-input-label for="password" value="Nueva contraseña" />
                            <x-text-input id="password" type="password" name="password"
                                required autocomplete="new-password" />
                            <x-input-error :messages="$errors->get('password')" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="password_confirmation"
                                value="Confirmar nueva contraseña" />
                            <x-text-input id="password_confirmation" type="password"
                                name="password_confirmation" required autocomplete="new-password" />
                            <x-input-error :messages="$errors->get('password_confirmation')" />
                        </div>

                        <div class="d-grid">
                            <x-primary-button>Ingresar</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
