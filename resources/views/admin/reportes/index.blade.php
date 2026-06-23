<x-app-layout>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="fw-bold h2 mb-0">Estadisticas</h1>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.reportes.export', 'csv') }}" class="btn btn-outline-secondary">
                <i class="bi bi-download me-1"></i>CSV
            </a>
            <a href="{{ route('admin.reportes.export', 'excel') }}" class="btn btn-outline-secondary">
                <i class="bi bi-download me-1"></i>Excel
            </a>
            <a href="{{ route('admin.reportes.export', 'pdf') }}" class="btn btn-outline-secondary">
                <i class="bi bi-file-earmark-pdf me-1"></i>PDF
            </a>
        </div>
    </div>

    <div class="row g-4">
        {{-- Gráfico: Usuarios por mes --}}
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">Usuarios por mes</h5>
                    <canvas id="chartUsuarios" height="220"></canvas>
                </div>
            </div>
        </div>

        {{-- Gráfico: Recetas más vistas --}}
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-body">
                    <h5 class="fw-bold mb-3">Recetas mas vistas</h5>
                    <canvas id="chartRecetas" height="220"></canvas>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script id="chart-data-meses" type="application/json">@json($usuariosPorMes->pluck('mes'))</script>
    <script id="chart-data-totals" type="application/json">@json($usuariosPorMes->pluck('total'))</script>
    <script id="chart-data-receta-labels" type="application/json">@json($recetasMasVistas->pluck('title'))</script>
    <script id="chart-data-receta-counts" type="application/json">@json($recetasMasVistas->pluck('rating_count'))</script>
    <script>
    (function () {
        function initCharts() {
            if (typeof Chart === 'undefined') {
                setTimeout(initCharts, 100);
                return;
            }

            const meses  = JSON.parse(document.getElementById('chart-data-meses').textContent);
            const totals = JSON.parse(document.getElementById('chart-data-totals').textContent);

            new Chart(document.getElementById('chartUsuarios'), {
                type: 'line',
                data: {
                    labels: meses,
                    datasets: [{
                        label: 'Nuevos usuarios',
                        data: totals,
                        borderColor: '#6c757d',
                        backgroundColor: 'rgba(108,117,125,0.08)',
                        borderWidth: 2,
                        tension: 0.3,
                        pointRadius: 4,
                        fill: true,
                    }],
                },
                options: {
                    responsive: true,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { beginAtZero: true, ticks: { precision: 0 } },
                    },
                },
            });

            const recetaLabels = JSON.parse(document.getElementById('chart-data-receta-labels').textContent);
            const recetaCounts = JSON.parse(document.getElementById('chart-data-receta-counts').textContent);

            new Chart(document.getElementById('chartRecetas'), {
                type: 'bar',
                data: {
                    labels: recetaLabels,
                    datasets: [{
                        label: 'Reseñas',
                        data: recetaCounts,
                        backgroundColor: 'rgba(108,117,125,0.5)',
                        borderColor: '#6c757d',
                        borderWidth: 1,
                    }],
                },
                options: {
                    responsive: true,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { beginAtZero: true, ticks: { precision: 0 } },
                    },
                },
            });
        }

        initCharts();
    })();
    </script>
    @endpush
</x-app-layout>
