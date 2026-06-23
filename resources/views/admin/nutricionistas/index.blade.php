<x-app-layout>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="fw-bold h2 mb-0">Nutricionistas</h1>
        <a href="{{ route('admin.nutricionistas.create') }}" class="btn btn-outline-secondary">
            <i class="bi bi-plus me-1"></i>Nuevo +
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if ($nutricionistas->isEmpty())
        <p class="text-muted">No hay nutricionistas registrados aún.</p>
    @else
        <div class="row g-3">
            @foreach ($nutricionistas as $nutricionista)
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="fw-bold mb-1">{{ $nutricionista->name }}</h5>
                            <p class="text-muted small mb-2">
                                <a href="mailto:{{ $nutricionista->email }}" class="text-muted">{{ $nutricionista->email }}</a>
                                @if ($nutricionista->specialty)
                                    &mdash; {{ $nutricionista->specialty }}
                                @endif
                            </p>
                            <p class="mb-3">
                                <strong>{{ $nutricionista->patient_count }}</strong> pacientes
                            </p>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="d-flex gap-2">
                                    <a href="{{ route('admin.nutricionistas.edit', $nutricionista) }}" class="btn btn-outline-secondary btn-sm">Editar</a>

                                    <form method="POST"
                                          action="{{ route('admin.nutricionistas.toggle', $nutricionista) }}"
                                          class="d-inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-outline-secondary btn-sm">
                                            {{ $nutricionista->active ? 'Desactivar' : 'Activar' }}
                                        </button>
                                    </form>
                                </div>
                                <span class="small text-muted">
                                    {{ $nutricionista->active ? 'Active' : 'Inactive' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</x-app-layout>
