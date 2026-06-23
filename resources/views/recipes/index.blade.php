<x-public-layout>
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <h1 class="fw-bold h2 mb-1">Recetas</h1>
            <p class="text-muted mb-0">{{ $recipes->total() }} resultados</p>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-4">
            <div class="sticky-top" style="top: 100px; z-index: 10;">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-body p-4" x-data="{ max_time: {{ request('max_time', 120) }}, max_calories: {{ request('max_calories', 900) }}, loading: false }">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h5 class="fw-bold mb-0"><i class="bi bi-sliders2 me-2"></i>Filtros</h5>
                            <a href="{{ route('recipes.index') }}" class="text-decoration-none small text-muted">Limpiar</a>
                        </div>

                        <form method="GET" action="{{ route('recipes.index') }}" @submit="loading = true">
                            {{-- Mis Ingredientes --}}
                            <div class="mb-4">
                                <label class="form-label small fw-bold text-uppercase mb-2 text-muted" style="letter-spacing: 0.05em;">
                                    <i class="bi bi-basket2 me-1"></i> Tengo en casa
                                </label>
                                @php
                                    $ingredientNames = $ingredients->pluck('name')->values()->all();
                                @endphp
                                <script>
                                    window.__ingredients = {!! json_encode($ingredientNames, JSON_UNESCAPED_UNICODE) !!};
                                    window.__myIngredients = {!! json_encode($myIngredients ?: [], JSON_UNESCAPED_UNICODE) !!};
                                </script>
                                <div x-data="{
                                    query: '',
                                    selected: window.__myIngredients,
                                    ingredients: window.__ingredients,
                                    showDropdown: false,
                                    get filteredIngredients() {
                                        if (this.query.trim() === '') return [];
                                        const normalize = s => s.toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '');
                                        const q = normalize(this.query);
                                        return this.ingredients.filter(i =>
                                            normalize(i).includes(q) && !this.selected.includes(i)
                                        );
                                    },
                                    selectIngredient(name) {
                                        if (!this.selected.includes(name)) {
                                            this.selected.push(name);
                                        }
                                        this.query = '';
                                        this.showDropdown = false;
                                        this.$refs.input.focus();
                                    },
                                    removeIngredient(name) {
                                        this.selected = this.selected.filter(i => i !== name);
                                        this.$refs.input.focus();
                                    }
                                }" class="position-relative" @click.away="showDropdown = false">
                                    <input type="hidden" name="my_ingredients" x-bind:value="selected.join(', ')">

                                    <template x-for="(item, index) in selected" :key="index">
                                        <span class="d-inline-flex align-items-center gap-1 bg-light border rounded-pill px-3 py-1 me-1 mb-1 small">
                                            <span x-text="item"></span>
                                            <button type="button" class="btn-close btn-close-sm" @click="removeIngredient(item)" style="font-size: 0.5em;"></button>
                                        </span>
                                    </template>

                                    <input type="text" x-ref="input" x-model="query"
                                           @focus="showDropdown = true"
                                           @input="showDropdown = true"
                                           @keydown.enter.prevent="if (filteredIngredients.length) { selectIngredient(filteredIngredients[0]); }"
                                           @keydown.escape="showDropdown = false"
                                           class="form-control border-0 bg-light p-3"
                                           style="border-radius: 10px; font-size: 0.9rem;"
                                           placeholder="Buscar ingrediente...">

                                    <ul x-ref="dropdown" x-show="showDropdown && filteredIngredients.length"
                                        class="list-group position-absolute w-100 shadow-sm mt-1"
                                        style="z-index: 1050; max-height: 220px; overflow-y: auto;" x-cloak>
                                        <template x-for="(item, index) in filteredIngredients" :key="index">
                                            <li class="list-group-item list-group-item-action small px-3 py-2"
                                                @click="selectIngredient(item)"
                                                x-text="item">
                                            </li>
                                        </template>
                                    </ul>

                                    <small class="text-muted d-block mt-2" style="font-size: 0.7rem; line-height: 1.2;">
                                        Ordenaremos las recetas priorizando las que usen lo que ya tienes.
                                    </small>
                                </div>
                            </div>

                            {{-- Búsqueda --}}
                            <div class="mb-4">
                                <label class="form-label small fw-bold text-uppercase mb-2 text-muted" style="letter-spacing: 0.05em;">
                                    <i class="bi bi-search me-1"></i> Buscar Receta
                                </label>
                                <input id="search" name="search" type="text" class="form-control border-0 bg-light p-2 px-3" value="{{ request('search') }}" placeholder="Ej. ensalada, pollo...">
                            </div>

                            {{-- Categoría --}}
                            <div class="mb-4">
                                <label class="form-label small fw-bold text-uppercase mb-2 text-muted" style="letter-spacing: 0.05em;">
                                    <i class="bi bi-tag me-1"></i> Categoría
                                </label>
                                <select id="category" name="category" class="form-select border-0 bg-light p-2 px-3">
                                    <option value="">Todas las categorías</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->slug }}" @selected(request('category') === $category->slug)>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Tiempo --}}
                            <div class="mb-4">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <label class="form-label small fw-bold text-uppercase mb-0 text-muted" style="letter-spacing: 0.05em;">
                                        <i class="bi bi-clock me-1"></i> Tiempo Máximo
                                    </label>
                                    <span class="badge bg-light text-primary border rounded-pill" x-text="max_time + ' min'"></span>
                                </div>
                                <div class="px-2">
                                    <input type="range" class="form-range" min="10" max="180" step="5" name="max_time" x-model="max_time">
                                    <div class="d-flex justify-content-between text-muted small mt-1" style="font-size: 0.7rem;">
                                        <span>10m</span>
                                        <span>180m</span>
                                    </div>
                                </div>
                            </div>

                            {{-- Calorías --}}
                            <div class="mb-4">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <label class="form-label small fw-bold text-uppercase mb-0 text-muted" style="letter-spacing: 0.05em;">
                                        <i class="bi bi-fire me-1"></i> Calorías Máximas
                                    </label>
                                    <span class="badge bg-light text-primary border rounded-pill" x-text="max_calories + ' kcal'"></span>
                                </div>
                                <div class="px-2">
                                    <input type="range" class="form-range" min="100" max="1200" step="50" name="max_calories" x-model="max_calories">
                                    <div class="d-flex justify-content-between text-muted small mt-1" style="font-size: 0.7rem;">
                                        <span>100</span>
                                        <span>1200</span>
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-dark w-100 py-2 fw-bold mt-2">
                                <span x-show="loading" class="spinner-border spinner-border-sm me-1" role="status" x-cloak></span>
                                <span x-show="!loading"><i class="bi bi-funnel me-1"></i> Aplicar Filtros</span>
                                <span x-show="loading">Cargando...</span>
                            </button>
                        </form>
                    </div>
                </div>

                <div class="card border-0 shadow-sm" style="background: linear-gradient(45deg, var(--nc-primary), var(--nc-secondary));">
                    <div class="card-body p-4 text-white">
                        <h6 class="fw-bold mb-2"><i class="bi bi-lightbulb me-2"></i>Tip de nutrición</h6>
                        <p class="small mb-0 opacity-75">Las recetas con menos de 30 min y 500 kcal son ideales para cenas ligeras entre semana.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <span class="text-muted small">Mostrando recetas {{ $recipes->firstItem() }}-{{ $recipes->lastItem() }}</span>
                <div>
                    {{ $recipes->links() }}
                </div>
            </div>

            <div class="row g-3">
                @forelse ($recipes as $index => $recipe)
                    <div class="col-md-6 nc-fade-in" style="animation-delay: {{ $index * 0.05 }}s">
                        <a href="{{ route('recipes.show', $recipe) }}" class="text-decoration-none text-dark">
                            <div class="card h-100 nc-card-hover border-0 shadow-sm">
                                @if($recipe->image_url)
                                    <div class="ratio ratio-4x3 rounded-top overflow-hidden">
                                        <img src="{{ $recipe->image_url }}" alt="{{ $recipe->title }}" class="w-100 h-100" style="object-fit: cover;">
                                    </div>
                                @else
                                    <div class="nc-img-placeholder rounded-top">🍽️</div>
                                @endif
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <span class="nc-badge">{{ $recipe->category?->name ?? 'General' }}</span>
                                        @if(isset($recipe->owned_count) && $recipe->owned_count > 0)
                                            <span class="badge bg-success-subtle text-success border-success-subtle border small rounded-pill">
                                                <i class="bi bi-check-all"></i> {{ $recipe->owned_count }} / {{ $recipe->ingredients->count() }}
                                            </span>
                                        @endif
                                    </div>
                                    <h5 class="fw-bold h6 text-truncate">{{ $recipe->title }}</h5>
                                    <div class="d-flex align-items-center gap-3 text-muted small mt-3">
                                        <span><i class="bi bi-clock"></i> {{ $recipe->prep_time_min ?? '-' }} min</span>
                                        <span><i class="bi bi-droplet"></i> {{ $recipe->calories ?? '-' }} kcal</span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                @empty
                    <div class="col-12 text-center py-5 nc-fade-in">
                        <div class="mb-4">
                            <i class="bi bi-search text-muted opacity-25" style="font-size: 5rem;"></i>
                        </div>
                        <h4 class="fw-bold text-muted">No encontramos recetas</h4>
                        <p class="text-muted mb-4">Prueba ajustando los filtros o buscando ingredientes diferentes.</p>
                        @if($isFiltered)
                            <a href="{{ route('recipes.index') }}" class="btn btn-outline-dark">
                                <i class="bi bi-x-circle me-1"></i> Limpiar todos los filtros
                            </a>
                        @endif
                    </div>
                @endforelse
            </div>

            <div class="mt-4">
                {{ $recipes->links() }}
            </div>
        </div>
    </div>
</x-public-layout>
