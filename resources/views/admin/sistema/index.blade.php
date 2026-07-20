<x-app-layout>
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h1 class="fw-bold h2 mb-1">Supervisi&oacute;n del Sistema</h1>
            <p class="text-muted small mb-0">Estado operativo y registro de actividad general de NutriChef.</p>
        </div>
    </div>

    {{-- Tarjetas de m&eacute;tricas --}}
    <div class="row g-3 mb-5">
        <div class="col-sm-6 col-xl-3">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <p class="text-muted small mb-1">
                        <i class="bi bi-activity text-success me-1"></i>Estado del Sitio
                    </p>
                    <div class="fs-4 fw-bold text-success">{{ $disponibilidad }}</div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <p class="text-muted small mb-1">
                        <i class="bi bi-people me-1"></i>Usuarios Activos
                    </p>
                    <div class="display-6 fw-bold">{{ $usuariosActivos }}</div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <p class="text-muted small mb-1">
                        <i class="bi bi-person-check me-1"></i>Usuarios en L&iacute;nea
                    </p>
                    <div class="display-6 fw-bold">{{ $usuariosEnLinea }}</div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card h-100 border-0 shadow-sm">
                <div class="card-body">
                    <p class="text-muted small mb-1">
                        <i class="bi bi-journal-check me-1"></i>Recetas Publicadas
                    </p>
                    <div class="display-6 fw-bold">{{ $recetasPublicadas }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Registro de Actividad --}}
    <h4 class="fw-bold mb-3">Registro de Actividad Reciente</h4>

    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3">Hora</th>
                        <th>Estado</th>
                        <th>Categor&iacute;a</th>
                        <th class="pe-3">Actividad Realizada</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($logs as $log)
                        <tr>
                            <td class="ps-3 text-muted font-monospace small">{{ $log['hora'] }}</td>
                            <td>
                                <span class="badge {{ $log['tipo'] === 'Aviso' ? 'bg-warning text-dark' : 'bg-success' }}">
                                    {{ $log['tipo'] }}
                                </span>
                            </td>
                            <td class="small fw-semibold">{{ $log['categoria'] }}</td>
                            <td class="pe-3 small text-muted">{{ $log['descripcion'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
