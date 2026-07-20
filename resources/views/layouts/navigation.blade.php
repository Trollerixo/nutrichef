@auth
@php $user = auth()->user(); @endphp

@if ($user->isAdmin())
    <p class="text-muted fw-semibold mb-2 px-1" style="font-size:.7rem;letter-spacing:.08em;text-transform:uppercase;">
        ADMINISTRACIÓN
    </p>
    <ul class="nav flex-column">
        <li class="nav-item">
            <a href="{{ route('dashboard') }}"
               class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="bi bi-house me-2"></i>Inicio
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('admin.recetas.index') }}" class="nav-link {{ request()->routeIs('admin.recetas.*') ? 'active' : '' }} d-flex align-items-center">
                <img src="{{ asset('images/icons/receta.svg') }}?v=3" alt="" class="nc-icon-sm me-2 nc-sidebar-icon" style="width: 1.35rem; height: 1.35rem;">Recetas
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('admin.categorias.index') }}" class="nav-link {{ request()->routeIs('admin.categorias.*') ? 'active' : '' }}"><i class="bi bi-tag me-2"></i>Categorías</a>
        </li>
        <li class="nav-item">
            <a href="{{ route('admin.usuarios.index') }}" class="nav-link {{ request()->routeIs('admin.usuarios.*') ? 'active' : '' }}"><i class="bi bi-people me-2"></i>Usuarios</a>
        </li>
        <li class="nav-item">
            <a href="{{ route('admin.nutricionistas.index') }}"
               class="nav-link {{ request()->routeIs('admin.nutricionistas.*') ? 'active' : '' }} d-flex align-items-center">
                <img src="{{ asset('images/icons/nutricionistas.svg') }}?v=3" alt="" class="nc-icon-sm me-2 nc-sidebar-icon">Nutricionistas
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('admin.comentarios.index') }}"
               class="nav-link {{ request()->routeIs('admin.comentarios.*') ? 'active' : '' }}">
                <i class="bi bi-chat-square-text me-2"></i>Comentarios
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('admin.reportes.index') }}"
               class="nav-link {{ request()->routeIs('admin.reportes.*') ? 'active' : '' }}">
                <i class="bi bi-bar-chart me-2"></i>Reportes
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('admin.notificaciones.index') }}"
               class="nav-link {{ request()->routeIs('admin.notificaciones.*') ? 'active' : '' }}">
                <i class="bi bi-bell me-2"></i>Notificaciones
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('admin.sistema.index') }}"
               class="nav-link {{ request()->routeIs('admin.sistema.*') ? 'active' : '' }}">
                <i class="bi bi-gear-wide me-2"></i>Sistema
            </a>
        </li>
    </ul>

@elseif ($user->isNutritionist())
    <p class="text-muted fw-semibold mb-2 px-1" style="font-size:.7rem;letter-spacing:.08em;text-transform:uppercase;">
        PANEL DEL NUTRICIONISTA
    </p>
    <ul class="nav flex-column">
        <li class="nav-item">
            <a href="{{ route('dashboard') }}"
               class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="bi bi-house me-2"></i>Inicio
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('nutritionist.patients.index') }}" class="nav-link {{ request()->routeIs('nutritionist.patients.*') ? 'active' : '' }}"><i class="bi bi-people me-2"></i>Pacientes</a>
        </li>
        <li class="nav-item">
            <a href="{{ route('nutritionist.plans.index') }}" class="nav-link {{ request()->routeIs('nutritionist.plans.*') ? 'active' : '' }}"><i class="bi bi-file-earmark-text me-2"></i>Crear plan</a>
        </li>
        <li class="nav-item">
            <a href="{{ route('nutritionist.recommendations.index') }}" class="nav-link {{ request()->routeIs('nutritionist.recommendations.*') ? 'active' : '' }}"><i class="bi bi-send me-2"></i>Recomendar</a>
        </li>
        <li class="nav-item">
            <a href="{{ route('nutritionist.consultations.index') }}" class="nav-link {{ request()->routeIs('nutritionist.consultations.*') ? 'active' : '' }}"><i class="bi bi-chat-dots me-2"></i>Consultas</a>
        </li>
                    </ul>

@else
    <p class="text-muted fw-semibold mb-2 px-1" style="font-size:.7rem;letter-spacing:.08em;text-transform:uppercase;">
        MI NUTRICHEF
    </p>
    <ul class="nav flex-column">
        <li class="nav-item">
            <a href="{{ route('dashboard') }}"
               class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="bi bi-house me-2"></i>Inicio
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('favorites.index') }}" class="nav-link {{ request()->routeIs('favorites.*') ? 'active' : '' }}"><i class="bi bi-heart me-2"></i>Favoritas</a>
        </li>
        <li class="nav-item">
            <a href="{{ route('shopping.index') }}" class="nav-link {{ request()->routeIs('shopping.*') ? 'active' : '' }}"><i class="bi bi-cart3 me-2"></i>Lista de compras</a>
        </li>
        <li class="nav-item">
            <a href="{{ route('weekly-menus.index') }}" class="nav-link {{ request()->routeIs('weekly-menus.*') ? 'active' : '' }} d-flex align-items-center">
                <img src="{{ asset('images/icons/menu_semanal.svg') }}" alt="" class="nc-icon-sm me-2 nc-sidebar-icon">Menú semanal
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('history.index') }}" class="nav-link {{ request()->routeIs('history.*') ? 'active' : '' }} d-flex align-items-center">
                <img src="{{ asset('images/icons/historial.svg') }}" alt="" class="nc-icon-sm me-2 nc-sidebar-icon">Historial
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('messages.index') }}" class="nav-link {{ request()->routeIs('messages.*') ? 'active' : '' }}"><i class="bi bi-chat me-2"></i>Mensajes</a>
        </li>
        <li class="nav-item">
            <a href="{{ route('recommendations.index') }}" class="nav-link {{ request()->routeIs('recommendations.*') ? 'active' : '' }}"><i class="bi bi-stars me-2"></i>Recomendaciones</a>
        </li>
        <li class="nav-item">
            <a href="{{ route('profile.edit') }}"
               class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                <i class="bi bi-gear me-2"></i>Mi perfil
            </a>
        </li>
        <li class="nav-item">
            <a href="{{ route('support') }}"
               class="nav-link {{ request()->routeIs('support') ? 'active' : '' }}">
                <i class="bi bi-headset me-2"></i>Ayuda y Soporte
            </a>
        </li>
    </ul>
@endif
@endauth
