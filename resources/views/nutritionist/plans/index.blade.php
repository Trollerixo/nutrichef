<x-app-layout>
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <h1 class="fw-bold h2 mb-1">Planes</h1>
            <p class="text-muted mb-0">Menús semanales asignados a tus pacientes.</p>
        </div>
        <a href="{{ route('nutritionist.plans.create') }}" class="btn btn-dark">
            <img src="{{ asset('images/icons/crear_plan_alimenticio.svg') }}" alt="" class="nc-icon me-1">Nuevo plan
        </a>
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
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-secondary text-uppercase">{{ $menu->status }}</span>
                            <button type="button" class="btn btn-outline-dark btn-sm js-download-menu" title="Descargar plan como texto" aria-label="Descargar plan"
                                    data-title="{{ $menu->title }}"
                                    data-slots='@json($menu->slots->sortBy("slot_date")->map(fn($slot) => ["date" => optional($slot->slot_date)->format("d/m/Y"), "meal_type" => $slot->meal_type, "recipe" => $slot->recipe?->title ?? "Receta eliminada"]))'>
                                <img src="{{ asset('images/icons/descargar_plan_alimenticio.svg') }}" alt="" class="nc-icon-lg">
                            </button>
                        </div>
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

    @push('scripts')
    <script>
        function downloadWeeklyMenu(title, slots) {
            let content = title + '\n' + '='.repeat(title.length) + '\n\n';
            if (!slots.length) {
                content += 'Este plan aún no tiene recetas asignadas.\n';
            } else {
                slots.forEach(slot => {
                    content += slot.date + ' - ' + slot.meal_type + ': ' + slot.recipe + '\n';
                });
            }
            const blob = new Blob([content], { type: 'text/plain;charset=utf-8' });
            const url = URL.createObjectURL(blob);
            const link = document.createElement('a');
            link.href = url;
            link.download = title.replace(/[^a-z0-9]+/gi, '_') + '.txt';
            document.body.appendChild(link);
            link.click();
            link.remove();
            URL.revokeObjectURL(url);
        }

        document.querySelectorAll('.js-download-menu').forEach(function (btn) {
            btn.addEventListener('click', function () {
                const slots = JSON.parse(btn.dataset.slots || '[]');
                downloadWeeklyMenu(btn.dataset.title || 'Plan alimenticio', slots);
            });
        });
    </script>
    @endpush
</x-app-layout>
