<x-guest-layout>
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-sm">
                <div class="card-body p-5">
                    <h2 class="fw-bold mb-3">Verifica tu correo</h2>
                    <p class="text-muted mb-4">
                        Gracias por registrarte. Antes de continuar, revisa tu bandeja de entrada
                        y haz clic en el enlace de verificación que te enviamos.
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <div class="alert alert-success mb-4">
                            Se ha enviado un nuevo enlace de verificación a tu correo.
                        </div>
                    @endif

                    <form method="POST" action="{{ route('verification.send') }}">
                        @csrf
                        <div class="d-grid mb-3">
                            <x-primary-button>Reenviar enlace</x-primary-button>
                        </div>
                    </form>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <div class="d-grid">
                            <button type="submit" class="btn btn-outline-secondary">
                                Cerrar sesión
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
