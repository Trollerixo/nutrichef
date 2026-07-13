    <x-public-layout>
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="mb-3">
        <button onclick="history.back()" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left me-1"></i> Volver
        </button>
    </div>

    <div class="row g-4">
        <div class="col-lg-5">
            <div class="card">
                @if($recipe->image_url)
                    <div class="ratio ratio-4x3 rounded-top overflow-hidden">
                        <img src="{{ $recipe->image_url }}" alt="{{ $recipe->title }}" class="w-100 h-100" style="object-fit: cover;">
                    </div>
                @else
                    <div class="nc-img-placeholder rounded-top">🍽️</div>
                @endif
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <small class="text-muted nc-badge">{{ $recipe->category?->name ?? 'General' }}</small>
                            <h1 class="h4 fw-bold mt-2">{{ $recipe->title }}</h1>
                        </div>
                        <div class="text-end" x-data="{ loadingFav: false }">
                            @auth
                                @php $isFavorite = auth()->user()->favoriteRecipes()->where('recipe_id', $recipe->id)->exists(); @endphp
                                <form method="POST" action="{{ route('recipes.toggleFavorite', $recipe) }}" class="d-inline-block me-1" @submit="loadingFav = true">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-secondary btn-sm mb-2 btn-favorite {{ $isFavorite ? 'active' : '' }}" aria-label="{{ $isFavorite ? 'Quitar de favoritos' : 'Agregar a favoritos' }}">
                                        <span x-show="loadingFav" class="spinner-border spinner-border-sm" role="status" x-cloak></span>
                                        <span x-show="!loadingFav"><img src="{{ asset('images/icons/añadir_favorito.svg') }}" alt="" class="nc-icon-sm me-1">Favorito</span>
                                    </button>
                                </form>
                                <button type="button" class="btn btn-outline-secondary btn-sm mb-2"
                                        data-bs-toggle="modal" data-bs-target="#addToMenuModal"
                                        aria-label="Agregar al menú semanal">
                                    <i class="bi bi-calendar-plus"></i> Menú
                                </button>
                                <button type="button" class="btn btn-outline-secondary btn-sm mb-2" id="btn-compartir"
                                        aria-label="Compartir receta" title="Compartir receta">
                                    <img src="{{ asset('images/icons/compartir_receta.svg') }}" alt="" class="nc-icon-sm me-1">Compartir
                                </button>
                            @else
                                <a href="{{ route('login') }}" class="btn btn-outline-secondary btn-sm mb-2">Inicia sesión para acciones</a>
                            @endauth
                        </div>
                    </div>

                    <div class="mb-4">
                        <div class="d-flex flex-wrap gap-3 text-muted small">
                            <span><i class="bi bi-star-fill nc-rating"></i> {{ number_format($recipe->rating_avg, 1) ?? '0.0' }}</span>
                            <span><i class="bi bi-clock"></i> {{ $recipe->prep_time_min ?? '-' }} min</span>
                            <span><i class="bi bi-droplet"></i> {{ $recipe->calories ?? '-' }} kcal</span>
                        </div>
                    </div>

                    <h6 class="fw-semibold">Información Nutricional</h6>
                    <ul class="list-unstyled mb-4 text-muted small">
                        <li><i class="bi bi-fire me-2"></i> Calorías: {{ $recipe->calories ?? '—' }}</li>
                        <li><i class="bi bi-clock-history me-2"></i> Tiempo: {{ $recipe->prep_time_min ?? '—' }} min</li>
                        <li><i class="bi bi-capsule me-2"></i> Proteínas: {{ $recipe->nutrition?->proteins_g ?? '—' }} g</li>
                        <li><i class="bi bi-cup-straw me-2"></i> Carbohidratos: {{ $recipe->nutrition?->carbs_g ?? '—' }} g</li>
                    </ul>

                    <h6 class="fw-semibold">Ingredientes</h6>
                    <ul class="list-unstyled mb-4">
                        @forelse ($recipe->ingredients as $ingredient)
                            <li class="mb-2">
                                • {{ $ingredient->name }}
                                @if ($ingredient->pivot->quantity)
                                    — {{ $ingredient->pivot->quantity }}
                                @endif
                            </li>
                        @empty
                            <li class="text-muted">Sin ingredientes registrados.</li>
                        @endforelse
                    </ul>

                    @auth
                        <form method="POST" action="{{ route('recipes.addToShoppingList', $recipe) }}">
                            @csrf
                            <input type="hidden" name="my_ingredients" value="{{ $myIngredientsRaw ?? '' }}">
                            <button type="submit" class="btn btn-dark w-100">Generar lista de compras</button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-dark w-100">Inicia sesión para generar lista</a>
                    @endauth
                </div>
            </div>
        </div>

        <div class="col-lg-7">
            <div class="card h-100">
                <div class="card-body">
                    <h2 class="fw-bold">Pasos de Preparación</h2>
                    <ol class="mt-4">
                        @forelse ($recipe->steps as $step)
                            <li class="mb-3">
                                <p class="mb-1 fw-semibold">Paso {{ $step->step_number }}</p>
                                <p class="text-muted mb-0">{{ $step->instruction }}</p>
                            </li>
                        @empty
                            <li class="text-muted">No hay pasos registrados para esta receta.</li>
                        @endforelse
                    </ol>
                </div>
            </div>
        </div>
    </div>

    {{-- Reviews --}}
    <div class="row g-4 mt-2">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <h3 class="fw-bold h5 mb-0">Reseñas</h3>
                        <span class="badge bg-light text-dark border rounded-pill">{{ $recipe->reviews->count() }}</span>
                        <div class="ms-auto text-muted small">
                            <i class="bi bi-star-fill nc-rating"></i>
                            {{ number_format($recipe->rating_avg, 1) ?? '0.0' }}
                            ({{ $recipe->rating_count }})
                        </div>
                    </div>

                    @auth
                        @if ($userReview)
                            {{-- User's own review --}}
                            @php $review = $userReview; @endphp
                            <div class="rounded p-4 mb-4" style="background: #e8f5e9;">
                                <div class="d-flex align-items-start gap-3">
                                    <div class="flex-shrink-0">
                                        <span class="badge bg-white text-dark border rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; font-size: 0.9rem;">
                                            {{ auth()->user()->name[0] ?? '?' }}
                                        </span>
                                    </div>
                                    <div class="flex-grow-1 min-w-0">
                                        <div class="d-flex align-items-center gap-2 mb-1">
                                            <strong class="small">Tu reseña</strong>
                                            <span class="text-muted" style="font-size: 0.7rem;">·</span>
                                            <span class="text-muted" style="font-size: 0.7rem;">{{ $review->created_at->diffForHumans() }}</span>
                                            <span class="badge bg-success text-white ms-1" style="font-size: 0.6rem;">PUBLICADA</span>
                                        </div>
                                        <div class="mb-1">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <i class="bi {{ $i <= $review->rating ? 'bi-star-fill nc-rating' : 'bi-star text-secondary' }}" style="font-size: 0.75rem;"></i>
                                            @endfor
                                        </div>
                                        <p class="mb-2 small text-muted">{{ $review->comment }}</p>
                                        <form action="{{ route('recipes.review.destroy', $recipe) }}" method="POST" class="d-inline" onsubmit="return confirm('¿Eliminar tu reseña? Podrás publicar una nueva después.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger btn-sm">
                                                <i class="bi bi-trash me-1"></i> Eliminar reseña
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @else
                            {{-- New review form --}}
                            <div class="bg-light rounded p-4 mb-4">
                                <h6 class="fw-semibold mb-3">Deja tu reseña</h6>
                                <form action="{{ route('recipes.review', $recipe) }}" method="POST">
                                    @csrf
                                    <div class="mb-3" x-data="{ rating: {{ old('rating', 0) }} }">
                                        <label class="form-label small fw-semibold">Puntuación</label>
                                        <div class="d-flex gap-1 fs-4">
                                            <template x-for="i in 5" :key="i">
                                                <button type="button" class="btn btn-link text-decoration-none p-0 me-1"
                                                        :class="i <= rating ? 'text-warning' : 'text-secondary'"
                                                        :aria-label="'Calificar con ' + i + ' estrellas'"
                                                        @click="rating = i">
                                                    <i class="bi" :class="i <= rating ? 'bi-star-fill' : 'bi-star'"></i>
                                                </button>
                                            </template>
                                        </div>
                                        <input type="hidden" name="rating" x-model="rating">
                                        @error('rating')
                                            <div class="text-danger small mt-1">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <label for="comment" class="form-label small fw-semibold">Comentario</label>
                                        <textarea id="comment" name="comment" rows="3" class="form-control @error('comment') is-invalid @enderror" placeholder="¿Qué te pareció la receta?" required>{{ old('comment') }}</textarea>
                                        @error('comment')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <button type="submit" class="btn btn-dark btn-sm">
                                        <img src="{{ asset('images/icons/añadir_nota.svg') }}" alt="" class="nc-icon-sm me-1">Publicar
                                    </button>
                                </form>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-4 mb-4">
                            <p class="text-muted mb-2">
                                <a href="{{ route('login') }}" class="text-decoration-none fw-semibold">Inicia sesión</a> para dejar tu reseña.
                            </p>
                        </div>
                    @endauth

                    {{-- Other reviews --}}
                    @php $others = $recipe->reviews->where('user_id', '!=', auth()->id()); @endphp
                    @forelse ($others as $review)
                        <div class="d-flex gap-3 py-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                            <div class="flex-shrink-0">
                                <span class="badge bg-light text-dark border rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; font-size: 0.9rem;">
                                    {{ $review->user?->name[0] ?? '?' }}
                                </span>
                            </div>
                            <div class="flex-grow-1 min-w-0">
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <strong class="small">{{ $review->user?->name ?? 'Anónimo' }}</strong>
                                    <span class="text-muted" style="font-size: 0.7rem;">·</span>
                                    <span class="text-muted" style="font-size: 0.7rem;">{{ $review->created_at->diffForHumans() }}</span>
                                </div>
                                <div class="mb-1">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <i class="bi {{ $i <= $review->rating ? 'bi-star-fill nc-rating' : 'bi-star text-secondary' }}" style="font-size: 0.75rem;"></i>
                                    @endfor
                                </div>
                                <p class="mb-0 small text-muted">{{ $review->comment }}</p>
                            </div>
                        </div>
                    @empty
                        @if (!$userReview)
                            <p class="text-muted small text-center py-3 mb-0">Sé el primero en reseñar esta receta.</p>
                        @endif
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    {{-- Add to Menu Modal --}}
    @auth
        <div class="modal fade" id="addToMenuModal" tabindex="-1" aria-hidden="true" x-data="addToMenuData()">
            <div class="modal-dialog modal-sm modal-dialog-centered">
                <div class="modal-content">
                    <form method="POST" action="{{ route('recipes.addToMenu', $recipe) }}" @submit="loading = true">
                        @csrf
                        <div class="modal-header">
                            <h6 class="modal-title">Agregar al menú semanal</h6>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            @if($activeMenu)
                                <p class="small text-muted mb-3">
                                    Se agregará a <strong>{{ $activeMenu->title }}</strong>
                                    <a href="{{ route('weekly-menus.index') }}" class="text-decoration-none small">(cambiar)</a>
                                </p>
                            @else
                                <p class="small text-muted mb-3">Se creará un nuevo menú activo.</p>
                            @endif
                            <div class="mb-3">
                                <label class="form-label small fw-semibold">Fecha</label>
                                <input type="date" name="slot_date" x-model="slotDate"
                                       :min="today" :max="maxDate"
                                       class="form-control form-control-sm" required>
                                @error('slot_date')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-semibold">Tipo de comida</label>
                                <select name="meal_type" x-model="mealType" class="form-select form-select-sm" required>
                                    <option value="">Selecciona</option>
                                    <option value="desayuno">Desayuno</option>
                                    <option value="almuerzo">Almuerzo</option>
                                    <option value="cena">Cena</option>
                                    <option value="postre">Postre</option>
                                    <option value="piqueo">Piqueo / Snack</option>
                                </select>
                                @error('meal_type')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <template x-if="duplicateType">
                                <div class="alert alert-warning py-2 small mb-0">
                                    ⚠ Ya hay <span x-text="duplicateType.count"></span> "<span x-text="duplicateType.name"></span>" para esta fecha.
                                </div>
                            </template>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-dark btn-sm" :disabled="loading">
                                <span x-show="loading" class="spinner-border spinner-border-sm"></span>
                                <span x-show="!loading">Agregar</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <script id="menu-slots-data" type="application/json">@json($activeMenu?->slots ?? [])</script>
        <script>
        function addToMenuData() {
            const typeLabels = {
                desayuno: 'Desayuno',
                almuerzo: 'Almuerzo',
                cena: 'Cena',
                postre: 'Postre',
                piqueo: 'Piqueo / Snack'
            };
            const existingSlots = JSON.parse(document.getElementById('menu-slots-data').textContent);
            return {
                today: new Date().toISOString().split('T')[0],
                maxDate: new Date(Date.now() + 6 * 86400000).toISOString().split('T')[0],
                slotDate: new Date().toISOString().split('T')[0],
                mealType: '',
                loading: false,
                get duplicateType() {
                    if (!this.slotDate || !this.mealType) return null;
                    const count = existingSlots.filter(s =>
                        s.slot_date === this.slotDate && s.meal_type === this.mealType
                    ).length;
                    if (count > 0) {
                        return { count, name: typeLabels[this.mealType] || this.mealType };
                    }
                    return null;
                }
            };
        }
        </script>
    @endauth

@push('scripts')
<script>
    document.getElementById('btn-compartir')?.addEventListener('click', function () {
        const url  = window.location.href;
        const title = document.querySelector('h1')?.innerText || 'Receta NutriChef';
        if (navigator.share) {
            navigator.share({ title, url }).catch(() => {});
        } else {
            navigator.clipboard.writeText(url).then(() => {
                const btn = document.getElementById('btn-compartir');
                const original = btn.innerHTML;
                btn.innerHTML = '<i class="bi bi-check2 me-1"></i>¡Copiado!';
                btn.disabled = true;
                setTimeout(() => { btn.innerHTML = original; btn.disabled = false; }, 2000);
            });
        }
    });
</script>
@endpush
</x-public-layout>
