<x-app-layout>
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <h1 class="fw-bold h2 mb-1">Pacientes</h1>
            <p class="text-muted mb-0">Lista de pacientes asignados.</p>
        </div>
    </div>

    @if ($patients->isEmpty())
        <div class="alert alert-secondary">Aún no tienes pacientes asignados.</div>
    @else
        <div class="row g-3">
            @foreach ($patients as $patient)
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-body">
                            <h5 class="fw-semibold">{{ $patient->name }}</h5>
                            <p class="text-muted mb-1">{{ $patient->email }}</p>
                            <p class="text-muted small">{{ $patient->profile?->bio ?? 'Sin información adicional.' }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</x-app-layout>
