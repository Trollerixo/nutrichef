<x-app-layout>
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <h1 class="fw-bold h2 mb-1">Usuarios</h1>
            <p class="text-muted mb-0">Listado de usuarios registrados y sus roles.</p>
        </div>
        <a href="{{ route('admin.usuarios.create') }}" class="btn btn-dark">Crear usuario</a>
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
                            <td>{{ $user->active ? 'Sí' : 'No' }}</td>
                            <td class="text-end">
                                <a href="{{ route('admin.usuarios.edit', $user) }}" class="btn btn-outline-secondary btn-sm">Editar</a>
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
