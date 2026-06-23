<x-app-layout>
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <h1 class="fw-bold h2 mb-1">Menú semanal</h1>
            <p class="text-muted mb-0">Revisa tus menús y recetas asignadas para la semana.</p>
        </div>
        <a href="{{ route('weekly-menus.create') }}" class="btn btn-dark">Crear nuevo menú</a>
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
                        <a href="{{ route('weekly-menus.edit', $menu) }}" class="btn btn-outline-secondary btn-sm">Editar</a>
                        <button type="button" class="btn btn-outline-danger btn-sm"
                                @click="triggerDelete('{{ route('weekly-menus.destroy', $menu) }}', '¿Eliminar menú?', 'Se borrará el menú semanal completo y toda su planificación.')">
                            Eliminar
                        </button>
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
                    @endphp
                    @foreach ($grouped as $date => $daySlots)
                        <div class="mb-3">
                            <h6 class="text-muted border-bottom pb-1">
                                {{ \Carbon\Carbon::parse($date)->locale('es')->isoFormat('dddd D [de] MMMM') }}
                            </h6>
                            <div class="row g-2">
                                @foreach ($daySlots as $slot)
                                    <div class="col-md-6">
                                        <div class="card border-0 shadow-sm">
                                            <div class="card-body py-2">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <div class="d-flex align-items-center gap-2">
                                                        <span class="nc-badge">{{ $slot->meal_type }}</span>
                                                        <span class="small fw-semibold">{{ $slot->recipe?->title ?? 'Receta eliminada' }}</span>
                                                    </div>
                                                    @if($slot->recipe)
                                                        <button type="button" class="btn btn-link text-danger p-0"
                                                                @click="triggerDelete('{{ route('weekly-menus.slots.destroy', $slot) }}', '¿Quitar del menú?', '¿Deseas eliminar esta receta de tu planificación del día?')">
                                                            <i class="bi bi-x-circle"></i>
                                                        </button>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    @empty
        <div class="alert alert-secondary">
            Aún no tienes un menú semanal. Crea uno o añade recetas desde una receta para comenzar.
        </div>
    @endforelse
</x-app-layout>
