<x-app-layout>
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <h1 class="fw-bold h2 mb-1">Usuarios</h1>
            <p class="text-muted mb-0">Listado de usuarios registrados y sus roles.</p>
        </div>
        <a href="{{ route('admin.usuarios.create') }}" class="btn btn-dark">
        <img src="{{ asset('images/icons/crear_o_registrar_usuario.svg') }}" alt="" class="nc-icon me-1">Crear usuario
        </a>
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table mb-0 align-middle">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Rol</th>
                        <th>Activo</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->role?->name ?? 'N/A' }}</td>
                            <td>
                                @if($user->active)
                                    <span class="d-inline-flex align-items-center gap-1 text-success small fw-semibold">
                                        <img src="{{ asset('images/icons/habilitar_usuario.svg') }}" alt="" class="nc-icon-sm">Activo
                                    </span>
                                @else
                                    <span class="d-inline-flex align-items-center gap-1 text-danger small fw-semibold">
                                        <img src="{{ asset('images/icons/bloquear_usuario.svg') }}" alt="" class="nc-icon-sm">Inactivo
                                    </span>
                                @endif
                            </td>
                            <td class="text-end">
                                <a href="{{ route('admin.usuarios.edit', $user) }}" class="btn btn-outline-secondary btn-sm me-2">
                                    <img src="{{ asset('images/icons/editar_perfil_de_usuario.svg') }}" alt="" class="nc-icon-sm me-1">Editar
                                </a>
                                @if ($user->id !== auth()->id())
                                    <form action="{{ route('admin.usuarios.destroy', $user) }}" method="POST" class="d-inline-block" onsubmit="return confirm('¿Eliminar a {{ $user->name }}? Esta acción no se puede deshacer.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm">
                                            <img src="{{ asset('images/icons/eliminar_usuario.svg') }}" alt="" class="nc-icon-sm me-1">Eliminar
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-4">
        {{ $users->links() }}
    </div>
</x-app-layout>
