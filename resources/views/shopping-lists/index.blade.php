<x-app-layout>
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <h1 class="fw-bold h2 mb-1">Listas de compras</h1>
            <p class="text-muted mb-0">Gestiona tus listas y agrega ingredientes manualmente o desde las recetas.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card mb-4">
        <div class="card-body">
            <form method="POST" action="{{ route('shopping.store') }}">
                @csrf
                <div class="row g-3 align-items-end">
                    <div class="col-sm-8">
                        <label class="form-label fw-semibold" for="title">Nueva lista</label>
                        <input id="title" name="title" class="form-control" value="Lista de compras" placeholder="Título de la lista (ej. Compras Semanales)">
                    </div>
                    <div class="col-sm-4 d-grid">
                        <button type="submit" class="btn btn-dark">
                        <img src="{{ asset('images/icons/crear_lista_de_compras.svg') }}" alt="" class="nc-icon me-1">Crear nueva lista
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @forelse ($lists as $list)
        <div class="card mb-4" x-data="{ editing: false, adding: false }">
            <div class="card-header bg-white py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="flex-grow-1">
                        <template x-if="!editing">
                            <div class="d-flex align-items-center gap-2">
                                <h5 class="mb-0 fw-bold">{{ $list->title }}</h5>
                                <button type="button" class="btn btn-outline-secondary btn-sm" @click="editing = true" title="Editar nombre" aria-label="Editar nombre de la lista">
                                    <i class="bi bi-pencil"></i>
                                </button>
                            </div>
                        </template>
                        <template x-if="editing">
                            <form action="{{ route('shopping.update', $list) }}" method="POST" class="d-flex gap-2">
                                @csrf
                                @method('PUT')
                                <input type="text" name="title" class="form-control form-control-sm w-50" value="{{ $list->title }}" autofocus>
                                <button type="submit" class="btn btn-primary btn-sm">
                                    <img src="{{ asset('images/icons/guardar_lista_de_compras.svg') }}" alt="" class="nc-icon-sm me-1">Guardar
                                </button>
                                <button type="button" class="btn btn-outline-secondary btn-sm" @click="editing = false">Cancelar</button>
                            </form>
                        </template>
                    </div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-outline-dark btn-sm" @click="adding = !adding">
                            <i class="bi bi-plus-lg me-1"></i> Ítem
                        </button>
                        <button type="button" class="btn btn-outline-dark btn-sm js-download-list" title="Descargar lista como texto" aria-label="Descargar lista"
                                data-title="{{ $list->title }}"
                                data-items='@json($list->items->map(fn($item) => ["name" => $item->name, "quantity" => $item->quantity]))'>
                            <img src="{{ asset('images/icons/descargar_lista_de_compras.svg') }}" alt="" class="nc-icon-lg">
                        </button>
                        <button type="button" class="btn btn-outline-danger btn-sm"
                                @click="triggerDelete('{{ route('shopping.destroy', $list) }}', '¿Eliminar lista?', 'Se borrará la lista \'{{ $list->title }}\' y todos sus productos.')">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </div>

                {{-- Formulario para agregar ítem manual --}}
                <div x-show="adding" class="mt-3 p-3 bg-light rounded border" x-cloak>
                    <form action="{{ route('shopping.items.add', $list) }}" method="POST">
                        @csrf
                        <div class="row g-2 align-items-end">
                            <div class="col-md-6">
                                <label class="small fw-semibold">Nombre del producto</label>
                                <input type="text" name="name" class="form-control form-control-sm" placeholder="Ej: Arroz" required>
                            </div>
                            <div class="col-md-4">
                                <label class="small fw-semibold">Cantidad</label>
                                <input type="text" name="quantity" class="form-control form-control-sm" placeholder="Ej: 1kg">
                            </div>
                            <div class="col-md-2 d-grid">
                                <button type="submit" class="btn btn-dark btn-sm">Añadir</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card-body p-0">
                @if ($list->items->isEmpty())
                    <div class="p-4 text-center">
                        <p class="text-muted mb-0">Esta lista está vacía. Agrega ítems manualmente o desde las recetas.</p>
                    </div>
                @else
                    <ul class="list-group list-group-flush">
                        @foreach ($list->items as $item)
                            <li class="list-group-item d-flex justify-content-between align-items-center py-3"
                                x-data="{
                                    checked: {{ $item->checked ? 'true' : 'false' }},
                                    toggle() {
                                        const previous = this.checked;
                                        axios.patch('/lista-compras/items/{{ $item->id }}/toggle')
                                            .then(response => {
                                                this.checked = response.data.checked;
                                                showToast(response.data.message);
                                            })
                                            .catch(() => {
                                                this.checked = previous;
                                            });
                                    }
                                }">
                                <div class="d-flex align-items-center">
                                    <div class="me-3">
                                        <input class="form-check-input" type="checkbox"
                                               x-model="checked"
                                               @change="toggle()"
                                               style="width: 1.25rem; height: 1.25rem; cursor: pointer;">
                                    </div>
                                    <div class="item-text" :class="{ 'text-decoration-line-through text-muted': checked }">
                                        <span class="fw-semibold">{{ $item->name }}</span>
                                        @if ($item->quantity)
                                            <span class="ms-2 px-2 py-1 bg-light rounded-pill border small">{{ $item->quantity }}</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-link text-danger p-0" title="Eliminar ítem"
                                            @click="triggerDelete('{{ route('shopping.items.destroy', $item) }}', '¿Eliminar ítem?', '¿Estás seguro de que deseas quitar \'{{ $item->name }}\' de la lista?')">
                                        <i class="bi bi-x-circle fs-5"></i>
                                    </button>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    @empty
        <div class="alert alert-secondary text-center py-5">
            <i class="bi bi-cart-x fs-1 d-block mb-3"></i>
            Aún no tienes listas de compras. Crea una arriba o agrega ingredientes desde una receta.
        </div>
    @endforelse
@push('scripts')
<script>
    function downloadShoppingList(title, items) {
        let content = title + '\n' + '='.repeat(title.length) + '\n\n';
        if (!items.length) {
            content += 'Esta lista está vacía.\n';
        } else {
            items.forEach(item => {
                content += '- ' + item.name + (item.quantity ? ' (' + item.quantity + ')' : '') + '\n';
            });
        }
        const blob = new Blob([content], { type: 'text/plain;charset=utf-8' });
        const url = URL.createObjectURL(blob);
        const link = document.createElement('a');
        link.href = url;
        link.download = title.replace(/[^a-z0-9]+/gi, '_') + '.txt';
        document.body.appendChild(link);
        link.click();
        link.remove();
        URL.revokeObjectURL(url);
    }

    document.querySelectorAll('.js-download-list').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const items = JSON.parse(btn.dataset.items || '[]');
            downloadShoppingList(btn.dataset.title || 'Lista de compras', items);
        });
    });



    function showToast(message) {
        let container = document.querySelector('.toast-container');
        if (!container) {
            container = document.createElement('div');
            container.className = 'toast-container position-fixed top-0 end-0 p-3';
            document.body.appendChild(container);
        }
        const toastEl = document.createElement('div');
        toastEl.className = 'toast nc-toast show';
        toastEl.setAttribute('role', 'alert');
        toastEl.setAttribute('aria-live', 'assertive');
        toastEl.setAttribute('aria-atomic', 'true');
        toastEl.innerHTML =
            '<div class="toast-header border-0 bg-transparent">' +
                '<i class="bi bi-check-circle-fill text-success me-2"></i>' +
                '<strong class="me-auto">NutriChef</strong>' +
                '<button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>' +
            '</div>' +
            '<div class="toast-body pt-0">' + message + '</div>';
        container.appendChild(toastEl);
        const toast = new bootstrap.Toast(toastEl, { autohide: true, delay: 4000 });
        toast.show();
        toastEl.addEventListener('hidden.bs.toast', () => toastEl.remove());
    }
</script>
@endpush

</x-app-layout>
