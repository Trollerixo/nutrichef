<x-app-layout>
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <h1 class="fw-bold h2 mb-1">Tu Actividad</h1>
            <p class="text-muted mb-0">Recetas que has visto, guardado o agregado a tus planes.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @php
        $actionIcons = [
            'viewed' => 'bi-eye',
            'favorited' => 'bi-heart-fill',
            'added_to_menu' => 'bi-calendar-plus',
            'added_to_shopping_list' => 'bi-cart-plus',
        ];
        $actionLabels = [
            'viewed' => 'Visto',
            'favorited' => 'Guardado',
            'added_to_menu' => 'Al Menú',
            'added_to_shopping_list' => 'A la Lista',
        ];
    @endphp

    @forelse ($history as $entry)
        <div class="card border-0 shadow-sm mb-3" x-data="{ open: false }">
            <a href="{{ $entry->recipe ? route('recipes.show', $entry->recipe) : '#' }}" class="text-decoration-none text-dark">
                <div class="card-body">
                    <div class="d-flex gap-3 align-items-center">
                        {{-- Thumbnail --}}
                        <div class="flex-shrink-0 rounded overflow-hidden" style="width: 64px; height: 64px;">
                            @if($entry->recipe && $entry->recipe->image_url)
                                <img src="{{ $entry->recipe->image_url }}" alt="{{ $entry->recipe->title }}" class="w-100 h-100" style="object-fit: cover;">
                            @else
                                <div class="w-100 h-100 d-flex align-items-center justify-content-center bg-light" style="font-size: 1.5rem;">🍽️</div>
                            @endif
                        </div>
                        {{-- Info --}}
                        <div class="flex-grow-1 min-w-0">
                            <h6 class="fw-bold mb-1 text-truncate">{{ $entry->recipe?->title ?? 'Receta eliminada' }}</h6>
                            <div class="d-flex flex-wrap gap-2 align-items-center">
                                @foreach($entry->actions as $action => $count)
                                    <span class="badge bg-light text-dark border small d-inline-flex align-items-center gap-1">
                                        <i class="{{ $actionIcons[$action] ?? 'bi-question-circle' }}"></i>
                                        {{ $actionLabels[$action] ?? $action }}
                                        @if($count > 1)
                                            <span class="badge bg-secondary text-white rounded-pill ms-1" style="font-size: 0.6rem;">{{ $count }}</span>
                                        @endif
                                    </span>
                                @endforeach
                                <small class="text-muted ms-auto">{{ $entry->last_seen->diffForHumans() }}</small>
                            </div>
                        </div>
                        {{-- Expand toggle --}}
                        <button type="button" class="btn btn-link text-muted p-0 flex-shrink-0" style="font-size: 0.8rem;"
                                @click.prevent="open = !open"
                                :class="{ 'rotate-180': open }">
                            <i class="bi bi-chevron-down"></i>
                        </button>
                    </div>
                </div>
            </a>
            {{-- Detail timeline --}}
            <div x-show="open" class="border-top bg-light">
                <div class="p-3" style="max-height: 300px; overflow-y: auto;">
                    <ul class="list-unstyled mb-0">
                        @foreach($entry->entries as $detail)
                            <li class="d-flex align-items-center gap-2 py-1 small">
                                <span class="badge bg-white text-dark border d-inline-flex align-items-center gap-1 px-2 py-1 flex-shrink-0" style="font-size: 0.65rem;">
                                    <i class="{{ $actionIcons[$detail->action] ?? 'bi-question-circle' }}"></i>
                                    {{ $actionLabels[$detail->action] ?? $detail->action }}
                                </span>
                                <span class="text-muted">{{ $detail->occurred_at->format('d/m/Y H:i') }}</span>
                                <span class="text-muted">·</span>
                                <span class="text-muted">{{ $detail->occurred_at->diffForHumans() }}</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    @empty
        <div class="text-center py-5">
            <i class="bi bi-clock-history fs-1 text-muted d-block mb-3"></i>
            <p class="text-muted">No hay actividad registrada aún. ¡Explora algunas recetas!</p>
            <a href="{{ route('recipes.index') }}" class="btn btn-dark btn-sm">Explorar recetas</a>
        </div>
    @endforelse

    <div class="mt-4 d-flex justify-content-center">
        {{ $history->links() }}
    </div>
</x-app-layout>
