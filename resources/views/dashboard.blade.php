<x-app-layout>
    @php $user = auth()->user(); @endphp

    {{-- Welcome Section --}}
    <div class="row mb-4 align-items-center">
        <div class="col-md-8">
            <h1 class="fw-bold h3 mb-1">¡Hola, {{ $user->name }}! 👋</h1>
            <p class="text-muted mb-0">Bienvenido de nuevo a tu panel de NutriChef. Aquí tienes un resumen de hoy.</p>
        </div>
        @if ($user->isUser())
            <div class="col-md-4 text-md-end mt-3 mt-md-0">
                <a href="{{ route('profile.edit') }}" class="btn btn-outline-dark btn-sm shadow-sm">
                    <i class="bi bi-person-gear me-1"></i> Mi Perfil
                </a>
            </div>
        @endif
    </div>

    @if ($user->isUser())
        {{-- Quick Stats for Patients --}}
        <div class="row g-4 mb-5">
            <div class="col-6 col-lg-3">
                <div class="card h-100 border-0 text-center p-3" style="background: linear-gradient(135deg, #d8f3dc 0%, #b7e4c7 100%);">
                    <div class="fs-2 mb-1">🥗</div>
                    <div class="fw-bold h5 mb-0">{{ $user->weeklyMenus()->count() }}</div>
                    <small class="text-secondary fw-semibold">Planes</small>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="card h-100 border-0 text-center p-3" style="background: linear-gradient(135deg, #fff3b0 0%, #fee440 100%);">
                    <div class="fs-2 mb-1">❤️</div>
                    <div class="fw-bold h5 mb-0">{{ $user->favoriteRecipes()->count() }}</div>
                    <small class="text-secondary fw-semibold">Favoritas</small>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="card h-100 border-0 text-center p-3" style="background: linear-gradient(135deg, #caf0f8 0%, #90e0ef 100%);">
                    <div class="fs-2 mb-1">🛒</div>
                    <div class="fw-bold h5 mb-0">{{ $user->shoppingLists()->count() }}</div>
                    <small class="text-secondary fw-semibold">Listas</small>
                </div>
            </div>
            <div class="col-6 col-lg-3">
                <div class="card h-100 border-0 text-center p-3" style="background: linear-gradient(135deg, #ffd7ba 0%, #ffb4a2 100%);">
                    <div class="fs-2 mb-1">✉️</div>
                    <div class="fw-bold h5 mb-0">{{ $user->consultationsAsPatient()->count() }}</div>
                    <small class="text-secondary fw-semibold">Consultas</small>
                </div>
            </div>
        </div>

        <div class="row g-4 mb-4">
            {{-- Quick Access --}}
            <div class="col-lg-8">
                <h5 class="fw-bold mb-3"><i class="bi bi-lightning-charge-fill text-warning me-2"></i>Acceso Rápido</h5>
                <div class="row g-3">
                    <div class="col-md-4">
                        <div class="card h-100 border-0 shadow-sm overflow-hidden">
                            <div class="row g-0 h-100">
                                <div class="col-4 bg-primary d-flex align-items-center justify-content-center text-white" style="background-color: var(--nc-primary) !important;">
                                    <i class="bi bi-search fs-1"></i>
                                </div>
                                <div class="col-8">
                                    <div class="card-body">
                                        <h6 class="fw-bold mb-1">Explorar Recetas</h6>
                                        <p class="text-muted small mb-2">Más de 1.200 ideas saludables.</p>
                                        <a href="{{ route('recipes.index') }}" class="btn btn-sm btn-link p-0 text-decoration-none fw-semibold" style="color: var(--nc-primary);">Ver todas →</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card h-100 border-0 shadow-sm overflow-hidden">
                            <div class="row g-0 h-100">
                                <div class="col-4 d-flex align-items-center justify-content-center text-white" style="background-color: var(--nc-accent) !important;">
                                    <i class="bi bi-chat-dots fs-1"></i>
                                </div>
                                <div class="col-8">
                                    <div class="card-body">
                                        <h6 class="fw-bold mb-1">Mensajes</h6>
                                        <p class="text-muted small mb-2">Consulta con tu nutricionista.</p>
                                        <a href="{{ route('messages.index') }}" class="btn btn-sm btn-link p-0 text-decoration-none fw-semibold" style="color: var(--nc-accent);">Ir a mensajes →</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card h-100 border-0 shadow-sm overflow-hidden">
                            <div class="row g-0 h-100">
                                <div class="col-4 bg-secondary d-flex align-items-center justify-content-center text-white" style="background-color: var(--nc-secondary) !important;">
                                    <i class="bi bi-calendar-check fs-1"></i>
                                </div>
                                <div class="col-8">
                                    <div class="card-body">
                                        <h6 class="fw-bold mb-1">Menú Semanal</h6>
                                        <p class="text-muted small mb-2">Organiza tus comidas de hoy.</p>
                                        <a href="{{ route('weekly-menus.index') }}" class="btn btn-sm btn-link p-0 text-decoration-none fw-semibold" style="color: var(--nc-secondary);">Ver plan →</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Recommendations --}}
            <div class="col-lg-4">
                @php
                    $latestRecs = $user->receivedRecommendations()
                        ->with(['nutritionist', 'recipe'])
                        ->latest('sent_at')
                        ->limit(3)
                        ->get();
                @endphp
                <div class="card h-100 border-0 shadow-sm" style="background-color: #f1f5f9;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="fw-bold mb-0">Recomendaciones</h6>
                            @if($latestRecs->isNotEmpty())
                                <a href="{{ route('recommendations.index') }}" class="btn btn-sm btn-link p-0 text-decoration-none fw-semibold" style="color: var(--nc-secondary);">Ver todas →</a>
                            @endif
                        </div>
                        @if($latestRecs->isEmpty())
                            <div class="text-center py-3">
                                <i class="bi bi-stars fs-1 text-warning mb-2"></i>
                                <p class="text-muted small">Tus nutricionistas te enviarán recomendaciones personalizadas.</p>
                                <a href="{{ route('recipes.index') }}" class="btn btn-dark btn-sm w-100">Explorar recetas</a>
                            </div>
                        @else
                            @foreach($latestRecs as $rec)
                                <div class="d-flex align-items-start gap-2 mb-2 pb-2 border-bottom border-light">
                                    <i class="bi bi-star-fill text-warning mt-1"></i>
                                    <div class="min-w-0">
                                        <div class="small fw-semibold text-truncate">
                                            @if($rec->recipe)
                                                <a href="{{ route('recipes.show', $rec->recipe) }}" class="text-decoration-none text-dark">{{ $rec->recipe->title }}</a>
                                            @else
                                                <span class="text-muted">Receta eliminada</span>
                                            @endif
                                        </div>
                                        <div class="text-muted" style="font-size: 0.7rem;">
                                            {{ $rec->nutritionist?->name ?? 'Nutricionista' }} · {{ $rec->sent_at->format('d M Y') }}
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </div>

    @elseif ($user->isNutritionist())
        {{-- Nutritionist Command Center --}}
        <div class="row g-4 mb-4">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm bg-primary text-white p-4" style="background-color: var(--nc-primary) !important;">
                    <h2 class="fw-bold mb-1">{{ $user->patientsUsers()->count() }}</h2>
                    <p class="mb-0 opacity-75">Pacientes Activos</p>
                    <i class="bi bi-people position-absolute end-0 bottom-0 mb-3 me-3 fs-1 opacity-25"></i>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm bg-warning text-dark p-4">
                    <h2 class="fw-bold mb-1">{{ $user->consultationsAsNutritionist()->whereIn('status', ['open', 'in_progress'])->count() }}</h2>
                    <p class="mb-0 opacity-75">Consultas Pendientes</p>
                    <i class="bi bi-chat-dots position-absolute end-0 bottom-0 mb-3 me-3 fs-1 opacity-25"></i>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm bg-info text-white p-4" style="background-color: var(--nc-secondary) !important;">
                    <h2 class="fw-bold mb-1">{{ $user->assignedMenus()->count() }}</h2>
                    <p class="mb-0 opacity-75">Planes Generados</p>
                    <i class="bi bi-journal-check position-absolute end-0 bottom-0 mb-3 me-3 fs-1 opacity-25"></i>
                </div>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white border-0 py-3">
                        <h6 class="fw-bold mb-0">Gestión de Pacientes</h6>
                    </div>
                    <div class="card-body">
                        <p class="text-muted small">Revisa el progreso de tus pacientes asignados y responde a sus dudas.</p>
                        <a href="{{ route('nutritionist.patients.index') }}" class="btn btn-outline-dark btn-sm">Ver lista de pacientes</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white border-0 py-3">
                        <h6 class="fw-bold mb-0">Acciones Rápidas</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('nutritionist.plans.create') }}" class="btn btn-light btn-sm text-start border"><i class="bi bi-plus-circle me-2"></i>Nuevo Plan</a>
                            <a href="{{ route('nutritionist.recommendations.create') }}" class="btn btn-light btn-sm text-start border"><i class="bi bi-send me-2"></i>Enviar Recomendación</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    @else
        {{-- Admin Dashboard --}}
        <div class="card border-0 shadow-sm p-5 text-center bg-white">
            <div class="nc-img-placeholder mb-4" style="height: 100px; background: none;">🛡️</div>
            <h4 class="fw-bold">Panel Administrativo</h4>
            <p class="text-muted mx-auto" style="max-width: 500px;">Control total del sistema NutriChef. Gestiona usuarios, categorías, recetas y reportes estadísticos.</p>
            <div class="d-flex justify-content-center gap-3 mt-4">
                <a href="{{ route('admin.recetas.index') }}" class="btn btn-dark btn-sm px-4">Recetas</a>
                <a href="{{ route('admin.usuarios.index') }}" class="btn btn-outline-dark btn-sm px-4">Usuarios</a>
            </div>
        </div>
    @endif

</x-app-layout>
