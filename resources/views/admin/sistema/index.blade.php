<x-app-layout>
    <h1 class="fw-bold h2 mb-4">Supervicion del Sistema</h1>

    {{-- Tarjetas de métricas --}}
    <div class="row g-3 mb-5">
        <div class="col-sm-6 col-xl-3">
            <div class="card h-100">
                <div class="card-body">
                    <p class="text-muted small mb-1">
                        <i class="bi bi-wifi me-1"></i>Uptime promedio
                    </p>
                    <div class="display-6 fw-bold">{{ $uptime }}</div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card h-100">
                <div class="card-body">
                    <p class="text-muted small mb-1">
                        <i class="bi bi-people me-1"></i>Usuarios activos
                    </p>
                    <div class="display-6 fw-bold">{{ $usuariosActivos }}</div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card h-100">
                <div class="card-body">
                    <p class="text-muted small mb-1">
                        <i class="bi bi-lightning me-1"></i>Sesiones ahora
                    </p>
                    <div class="display-6 fw-bold">{{ $sesionesAhora }}</div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card h-100">
                <div class="card-body">
                    <p class="text-muted small mb-1">
                        <i class="bi bi-journal-check me-1"></i>Recetas publicadas
                    </p>
                    <div class="display-6 fw-bold">{{ $recetasPublicadas }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Registro de logs --}}
    <h4 class="fw-bold mb-3">Registro reciente</h4>

    <div class="card">
        <div class="table-responsive">
            <table class="table table-sm mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th class="ps-3">Hora</th>
                        <th>Nivel</th>
                        <th>Servicio</th>
                        <th class="pe-3">Mensaje</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($logs as $log)
                        <tr>
                            <td class="ps-3 text-muted font-monospace small">{{ $log['hora'] }}</td>
                            <td>
                                <span class="badge
                                    {{ $log['nivel'] === 'WARN'  ? 'text-bg-warning'  : '' }}
                                    {{ $log['nivel'] === 'ERROR' ? 'text-bg-danger'   : '' }}
                                    {{ $log['nivel'] === 'INFO'  ? 'text-bg-light text-dark border' : '' }}
                                ">
                                    {{ $log['nivel'] }}
                                </span>
                            </td>
                            <td class="small">{{ $log['servicio'] }}</td>
                            <td class="pe-3 small">{{ $log['mensaje'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
