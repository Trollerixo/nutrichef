<x-app-layout>
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <h1 class="fw-bold h2 mb-1">Categorías</h1>
            <p class="text-muted mb-0">Administra las categorías disponibles para las recetas.</p>
        </div>
        <a href="{{ route('admin.categorias.create') }}" class="btn btn-outline-secondary">Nueva categoría</a>
    </div>

    <div class="card">
        <div class="table-responsive">
            <table class="table mb-0 align-middle">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Slug</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($categories as $category)
                        <tr>
                            <td>{{ $category->name }}</td>
                            <td>{{ $category->slug }}</td>
                            <td class="text-end">
                                <a href="{{ route('admin.categorias.edit', $category) }}" class="btn btn-outline-secondary btn-sm me-2">Editar</a>
                                <form action="{{ route('admin.categorias.destroy', $category) }}" method="POST" class="d-inline-block" onsubmit="return confirm('¿Eliminar esta categoría?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
