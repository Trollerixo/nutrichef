<x-app-layout>
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <h1 class="fw-bold h2 mb-1">Planes</h1>
            <p class="text-muted mb-0">Menús semanales asignados a tus pacientes.</p>
        </div>
        <a href="{{ route('nutritionist.plans.create') }}" class="btn btn-outline-secondary">Nuevo plan</a>
    </div>

    @if ($menus->isEmpty())
        <div class="alert alert-secondary">Todavía no has creado ningún plan.</div>
    @else
        @foreach ($menus as $menu)
            <div class="card mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-3">
                        <div>
                            <h5 class="fw-semibold">{{ $menu->title }}</h5>
                            <small class="text-muted">Paciente: {{ $menu->user?->name ?? 'N/A' }}</small>
                        </div>
                        <span class="badge bg-secondary text-uppercase">{{ $menu->status }}</span>
                    </div>
                    <p class="text-muted small mb-3">{{ $menu->notes }}</p>
                    <div class="row g-3">
                        @foreach ($menu->slots as $slot)
                            <div class="col-md-6">
                                <div class="card border-light">
                                    <div class="card-body">
                                        <p class="mb-1 small text-muted">{{ $slot->slot_date->format('d M Y') }}</p>
                                        <h6 class="fw-semibold mb-1">{{ $slot->recipe?->title ?? 'Receta eliminada' }}</h6>
                                        <span class="badge bg-secondary text-uppercase">{{ $slot->meal_type }}</span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endforeach
    @endif
</x-app-layout>
