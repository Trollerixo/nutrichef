<x-app-layout>
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <h1 class="fw-bold h2 mb-1">Menú semanal</h1>
            <p class="text-muted mb-0">Revisa tus menús y recetas asignadas para la semana.</p>
        </div>
        <a href="{{ route('weekly-menus.create') }}" class="btn btn-dark">
        <img src="{{ asset('images/icons/crear_menu_semanal.svg') }}" alt="" class="nc-icon me-1">Crear nuevo menú
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @forelse ($menus as $menu)
        <div class="card mb-4 {{ $menu->active ? 'border-primary border-2' : '' }}">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0">
                            {{ $menu->title }}
                            @if($menu->active)
                                <span class="badge bg-primary ms-2">ACTIVO</span>
                            @endif
                        </h5>
                        <small class="text-muted">{{ $menu->notes }}</small>
                    </div>
                    <div class="d-flex gap-2 align-items-center">
                        <span class="badge bg-success text-uppercase">{{ $menu->status }}</span>
                        @if(!$menu->active)
                            <form action="{{ route('weekly-menus.setActive', $menu) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-outline-primary btn-sm">Usar como activo</button>
                            </form>
                        @endif
                        <a href="{{ route('weekly-menus.edit', $menu) }}" class="btn btn-outline-secondary btn-sm">
                            <img src="{{ asset('images/icons/editar_plan_alimenticio.svg') }}" alt="" class="nc-icon-lg me-1">Editar
                        </a>
                        <button type="button" class="btn btn-outline-dark btn-sm js-download-menu" title="Descargar menú como texto" aria-label="Descargar menú"
                                data-title="{{ $menu->title }}"
                                data-slots='@json($menu->slots->sortBy("slot_date")->map(fn($slot) => ["date" => optional($slot->slot_date)->format("d/m/Y"), "meal_type" => $slot->meal_type, "recipe" => $slot->recipe?->title ?? "Receta eliminada"]))'>
                            <img src="{{ asset('images/icons/descargar_plan_alimenticio.svg') }}" alt="" class="nc-icon-lg">
                        </button>
                        <form action="{{ route('weekly-menus.destroy', $menu) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger btn-sm"
                                    onclick="return confirm('¿Eliminar menú? Se borrará el menú semanal completo y toda su planificación.')">
                                <img src="{{ asset('images/icons/eliminar_receta.svg') }}" alt="" class="nc-icon-lg me-1">Eliminar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="card-body">
                @if ($menu->slots->isEmpty())
                    <p class="text-muted mb-0">No hay recetas agregadas aún.</p>
                @else
                    @php
                        $grouped = $menu->slots->groupBy(function ($slot) {
                            return $slot->slot_date->format('Y-m-d');
                        })->sortKeys();
                        $hasToday = $grouped->keys()->contains(now()->format('Y-m-d'));
                    @endphp
                    <div class="accordion border-0" id="weeklyMenuAccordion-{{ $menu->id }}">
                        @foreach ($grouped as $date => $daySlots)
                            @php
                                $carbonDate = \Carbon\Carbon::parse($date);
                                $isToday = $date === now()->format('Y-m-d');
                                $isOpen = $isToday || (!$hasToday && $loop->first);
                            @endphp
                            <div class="accordion-item border-0 shadow-sm mb-3 rounded overflow-hidden">
                                <h2 class="accordion-header" id="heading-{{ $loop->index }}-{{ $menu->id }}">
                                    <button class="accordion-button py-3 px-4 bg-light text-dark fw-bold border-0 {{ $isOpen ? '' : 'collapsed' }}"
                                            type="button"
                                            data-bs-toggle="collapse"
                                            data-bs-target="#collapse-{{ $loop->index }}-{{ $menu->id }}"
                                            aria-expanded="{{ $isOpen ? 'true' : 'false' }}"
                                            aria-controls="collapse-{{ $loop->index }}-{{ $menu->id }}">
                                        <span class="text-capitalize">{{ $carbonDate->locale('es')->isoFormat('dddd D [de] MMMM') }}</span>
                                        @if($isToday)
                                            <span class="badge bg-success ms-2" style="font-size: 0.7rem; letter-spacing: 0.5px;">HOY</span>
                                        @endif
                                    </button>
                                </h2>
                                <div id="collapse-{{ $loop->index }}-{{ $menu->id }}"
                                     class="accordion-collapse collapse {{ $isOpen ? 'show' : '' }}"
                                     aria-labelledby="heading-{{ $loop->index }}-{{ $menu->id }}"
                                     data-bs-parent="#weeklyMenuAccordion-{{ $menu->id }}">
                                    <div class="accordion-body bg-white pt-3 pb-3">
                                        <div class="row g-2">
                                            @foreach ($daySlots as $slot)
                                                <div class="col-md-6">
                                                    <div class="card border-0 bg-light">
                                                        <div class="card-body py-2 px-3">
                                                            <div class="d-flex justify-content-between align-items-center">
                                                                <div class="d-flex align-items-center gap-2">
                                                                    <span class="nc-badge">{{ $slot->meal_type }}</span>
                                                                    <span class="small fw-semibold text-dark">{{ $slot->recipe?->title ?? 'Receta eliminada' }}</span>
                                                                </div>
                                                                @if($slot->recipe)
                                                                    <form action="{{ route('weekly-menus.slots.destroy', $slot) }}" method="POST" class="d-inline">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button type="submit" class="btn btn-link text-danger p-0"
                                                                                aria-label="Quitar del menú"
                                                                                onclick="return confirm('¿Quitar del menú? ¿Deseas eliminar esta receta de tu planificación del día?')">
                                                                            <i class="bi bi-x-circle fs-5"></i>
                                                                        </button>
                                                                    </form>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    @empty
        <div class="alert alert-secondary">
            Aún no tienes un menú semanal. Crea uno o añade recetas desde una receta para comenzar.
        </div>
    @endforelse

    @push('scripts')
    <script>
        function downloadWeeklyMenu(title, slots) {
            let content = title + '\n' + '='.repeat(title.length) + '\n\n';
            if (!slots.length) {
                content += 'Este menú aún no tiene recetas asignadas.\n';
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
                downloadWeeklyMenu(btn.dataset.title || 'Menú semanal', slots);
            });
        });
    </script>
    @endpush
</x-app-layout>
