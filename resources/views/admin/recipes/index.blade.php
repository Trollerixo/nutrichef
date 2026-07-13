<x-app-layout>
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <h1 class="fw-bold h2 mb-1">Recetas</h1>
            <p class="text-muted mb-0">Listado de recetas en el sistema.</p>
        </div>
        <a href="{{ route('admin.recetas.create') }}" class="btn btn-dark">
        <img src="{{ asset('images/icons/añadir_receta.svg') }}" alt="" class="nc-icon me-1">Nueva receta
        </a>
    </div>

    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>Título</th>
                    <th>Categoría</th>
                    <th>Autor</th>
                    <th>Estado</th>
                    <th>Publicada</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($recipes as $recipe)
                    <tr>
                        <td>{{ $recipe->title }}</td>
                        <td>{{ $recipe->category?->name ?? 'General' }}</td>
                        <td>{{ $recipe->author?->name ?? 'N/A' }}</td>
                        <td>{{ $recipe->published ? 'Publicada' : 'Borrador' }}</td>
                        <td>{{ optional($recipe->featured_date)->format('d/m/Y') ?? '—' }}</td>
                        <td class="text-end">
                            <a href="{{ route('admin.recetas.edit', $recipe) }}" class="btn btn-outline-secondary btn-sm me-2">
                                <img src="{{ asset('images/icons/gestionar_receta.svg') }}" alt="" class="nc-icon-sm me-1">Gestionar
                            </a>
                            <form action="{{ route('admin.recetas.destroy', $recipe) }}" method="POST" class="d-inline-block" onsubmit="return confirm('¿Eliminar esta receta?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-outline-danger btn-sm">
                                    <img src="{{ asset('images/icons/eliminar_receta.svg') }}" alt="" class="nc-icon-sm me-1">Eliminar
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $recipes->links() }}
    </div>
</x-app-layout>
