<x-public-layout>
    {{-- Hero --}}
    <section class="py-5 bg-white overflow-hidden nc-fade-in">
        <div class="container py-lg-5">
            <div class="row align-items-center g-5">
                <div class="col-md-6">
                    <h1 class="display-4 fw-bold lh-sm mb-3" style="color: var(--nc-primary);">
                        Come bien,<br>come sano,<br><span class="text-secondary">sin complicaciones.</span>
                    </h1>
                    <p class="lead text-muted mb-4">La plataforma definitiva para conectar con nutricionistas, organizar tus comidas y descubrir recetas deliciosas.</p>
                    <div class="mb-5">
                        <a href="{{ route('recipes.index') }}" class="btn btn-dark btn-lg px-5 shadow">
                            Explorar todas las recetas <i class="bi bi-arrow-right ms-2"></i>
                        </a>
                    </div>
                    <div class="d-flex gap-4 small text-muted">
                        <span><i class="bi bi-check2-circle text-success me-1"></i> +1.200 Recetas</span>
                        <span><i class="bi bi-check2-circle text-success me-1"></i> Plan Semanal</span>
                        <span><i class="bi bi-check2-circle text-success me-1"></i> Asesoría</span>
                    </div>
                </div>
                <div class="col-md-6" style="animation-delay: 0.2s">
                    <div class="position-relative">
                        @if($featuredRecipe)
                            <div class="rounded-circle shadow-sm mx-auto overflow-hidden"
                                 style="width:400px; height:400px; background: linear-gradient(135deg, #d8f3dc 0%, #b7e4c7 100%) !important;">
                                @if($featuredRecipe->image_url)
                                    <img src="{{ $featuredRecipe->image_url }}" alt="{{ $featuredRecipe->title }}" class="w-100 h-100" style="object-fit: cover;">
                                @else
                                    <div class="d-flex align-items-center justify-content-center w-100 h-100">
                                        <span class="display-1">🥗</span>
                                    </div>
                                @endif
                            </div>
                            <div class="card shadow-lg border-0 position-absolute nc-card-hover"
                                 style="bottom:2rem; left:-1rem; width:240px; border-radius: 15px;">
                                <div class="card-body p-3">
                                    <div class="d-flex align-items-center gap-2 mb-2">
                                        <div class="nc-badge" style="font-size: 0.6rem;">De la semana</div>
                                        <div class="text-warning small"><i class="bi bi-star-fill"></i> {{ number_format($featuredRecipe->rating_avg, 1) }}</div>
                                    </div>
                                    <p class="fw-bold mb-1 small">{{ $featuredRecipe->title }}</p>
                                    <div class="text-muted small" style="font-size: 0.7rem;">
                                        <i class="bi bi-clock"></i> {{ $featuredRecipe->prep_time_min ?? '-' }} min ·
                                        <i class="bi bi-droplet"></i> {{ $featuredRecipe->calories ?? '-' }} kcal
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="bg-light rounded-circle shadow-sm d-flex align-items-center justify-content-center mx-auto"
                                 style="width:400px; height:400px; background: linear-gradient(135deg, #d8f3dc 0%, #b7e4c7 100%) !important;">
                                <span class="display-1">🥗</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Featured recipes --}}
    <section class="py-5">
        <div class="container">
            <h3 class="fw-bold mb-4">Recetas destacadas</h3>
            <div class="row g-4">
                @forelse ($featuredRecipes as $recipe)
                    <div class="col-md-4">
                        <a href="{{ route('recipes.show', $recipe) }}" class="text-decoration-none text-dark">
                            <div class="card border-0 shadow-sm nc-card-hover h-100">
                                @if($recipe->image_url)
                                    <div class="ratio ratio-4x3 rounded-top overflow-hidden">
                                        <img src="{{ $recipe->image_url }}" alt="{{ $recipe->title }}" class="w-100 h-100" style="object-fit: cover;">
                                    </div>
                                @else
                                    <div class="nc-img-placeholder">🍽️</div>
                                @endif
                                <div class="card-body">
                                    <span class="nc-badge mb-2 d-inline-block">{{ $recipe->category?->name ?? 'General' }}</span>
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h5 class="fw-bold h6 mb-0">{{ $recipe->title }}</h5>
                                        <span class="text-warning small"><i class="bi bi-star-fill"></i> {{ number_format($recipe->rating_avg, 1) }}</span>
                                    </div>
                                    <p class="text-muted small mb-0">{{ $recipe->description }}</p>
                                </div>
                            </div>
                        </a>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-secondary mb-0">Todavía no hay recetas publicadas.</div>
                    </div>
                @endforelse
            </div>
        </div>
    </section>
</x-public-layout>
