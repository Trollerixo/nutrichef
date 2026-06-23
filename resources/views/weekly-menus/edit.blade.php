<x-app-layout>
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <h1 class="fw-bold h2 mb-1">Editar menú semanal</h1>
            <p class="text-muted mb-0">Actualiza los detalles y las recetas del menú.</p>
        </div>
        <a href="{{ route('weekly-menus.index') }}" class="btn btn-outline-secondary">Volver</a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('weekly-menus.update', $menu) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="title" class="form-label">Título</label>
                        <input id="title" name="title" type="text" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $menu->title) }}" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="status" class="form-label">Estado</label>
                        <select id="status" name="status" class="form-select @error('status') is-invalid @enderror">
                            <option value="published" {{ old('status', $menu->status) == 'published' ? 'selected' : '' }}>Publicado</option>
                            <option value="draft" {{ old('status', $menu->status) == 'draft' ? 'selected' : '' }}>Borrador</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-4">
                    <label for="notes" class="form-label">Notas</label>
                    <textarea id="notes" name="notes" rows="3" class="form-control @error('notes') is-invalid @enderror">{{ old('notes', $menu->notes) }}</textarea>
                    @error('notes')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <hr>
                <h5 class="mb-3">Recetas por día</h5>

                @php
                    $slotRows = old('slots');
                    if ($slotRows === null) {
                        $slotRows = $menu->slots->map(function ($slot) {
                            return [
                                'slot_date' => optional($slot->slot_date)->format('Y-m-d'),
                                'meal_type' => $slot->meal_type,
                                'recipe_id' => $slot->recipe_id,
                            ];
                        })->toArray();
                    }

                    if (empty($slotRows)) {
                        $slotRows = [['slot_date' => '', 'meal_type' => '', 'recipe_id' => '']];
                    }
                @endphp

                <div id="slotsContainer">
                    @foreach($slotRows as $index => $slot)
                        <div class="slot-row row g-3 align-items-end mb-3" data-existing="true">
                            <div class="col-md-4">
                                <label class="form-label">Fecha</label>
                                <input data-slot-field="slot_date" name="slots[{{ $index }}][slot_date]" type="date"
                                       class="form-control @error('slots.'.$index.'.slot_date') is-invalid @enderror"
                                       value="{{ $slot['slot_date'] ?? '' }}">
                                @error('slots.'.$index.'.slot_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Tipo de comida</label>
                                <select data-slot-field="meal_type" name="slots[{{ $index }}][meal_type]" class="form-select @error('slots.'.$index.'.meal_type') is-invalid @enderror">
                                    <option value="">Selecciona</option>
                                    <option value="desayuno" {{ ($slot['meal_type'] ?? '') == 'desayuno' ? 'selected' : '' }}>Desayuno</option>
                                    <option value="almuerzo" {{ ($slot['meal_type'] ?? '') == 'almuerzo' ? 'selected' : '' }}>Almuerzo</option>
                                    <option value="cena" {{ ($slot['meal_type'] ?? '') == 'cena' ? 'selected' : '' }}>Cena</option>
                                    <option value="postre" {{ ($slot['meal_type'] ?? '') == 'postre' ? 'selected' : '' }}>Postre</option>
                                    <option value="piqueo" {{ ($slot['meal_type'] ?? '') == 'piqueo' ? 'selected' : '' }}>Piqueo / Snack</option>
                                </select>
                                @error('slots.'.$index.'.meal_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Receta</label>
                                <select data-slot-field="recipe_id" name="slots[{{ $index }}][recipe_id]" class="form-select @error('slots.'.$index.'.recipe_id') is-invalid @enderror">
                                    <option value="">Selecciona una receta</option>
                                    @foreach($recipes as $recipe)
                                        <option value="{{ $recipe->id }}" {{ ($slot['recipe_id'] ?? '') == $recipe->id ? 'selected' : '' }}>{{ $recipe->title }}</option>
                                    @endforeach
                                </select>
                                @error('slots.'.$index.'.recipe_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-1 text-end">
                                <button type="button" class="btn btn-outline-danger btn-sm remove-slot-btn">&times;</button>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mb-4 text-end">
                    <button id="addSlotRow" type="button" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-plus-lg me-1"></i> Agregar receta
                    </button>
                </div>

                <template id="slotRowTemplate">
                    <div class="slot-row row g-3 align-items-end mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Fecha</label>
                            <input data-slot-field="slot_date" type="date" class="form-control"
                                   min="{{ now()->format('Y-m-d') }}" max="{{ now()->addDays(6)->format('Y-m-d') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Tipo de comida</label>
                            <select data-slot-field="meal_type" class="form-select">
                                <option value="">Selecciona</option>
                                <option value="desayuno">Desayuno</option>
                                <option value="almuerzo">Almuerzo</option>
                                <option value="cena">Cena</option>
                                <option value="postre">Postre</option>
                                <option value="piqueo">Piqueo / Snack</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Receta</label>
                            <select data-slot-field="recipe_id" class="form-select">
                                <option value="">Selecciona una receta</option>
                                @foreach($recipes as $recipe)
                                    <option value="{{ $recipe->id }}">{{ $recipe->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-1 text-end">
                            <button type="button" class="btn btn-outline-danger btn-sm remove-slot-btn">&times;</button>
                        </div>
                    </div>
                </template>

                <button type="submit" class="btn btn-dark">Actualizar menú semanal</button>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
    (function () {
        const slotsContainer = document.getElementById('slotsContainer');
        const addSlotButton = document.getElementById('addSlotRow');
        const template = document.getElementById('slotRowTemplate').content.firstElementChild;

        function refreshSlotNames() {
            slotsContainer.querySelectorAll('.slot-row').forEach((row, index) => {
                row.querySelectorAll('[data-slot-field]').forEach(field => {
                    field.name = `slots[${index}][${field.dataset.slotField}]`;
                });
            });
        }

        function getDuplicateWarning(row) {
            const date = row.querySelector('[data-slot-field="slot_date"]').value;
            const type = row.querySelector('[data-slot-field="meal_type"]').value;
            if (!date || !type) return null;
            let count = 0;
            slotsContainer.querySelectorAll('.slot-row').forEach(r => {
                if (r === row) return;
                if (r.querySelector('[data-slot-field="slot_date"]').value === date &&
                    r.querySelector('[data-slot-field="meal_type"]').value === type) {
                    count++;
                }
            });
            return count > 0 ? { date, type, count } : null;
        }

        function addSlotRow(slot = { slot_date: '', meal_type: '', recipe_id: '' }) {
            const clone = template.cloneNode(true);
            clone.querySelectorAll('[data-slot-field]').forEach(field => {
                field.value = slot[field.dataset.slotField] || '';
            });
            slotsContainer.appendChild(clone);
            refreshSlotNames();
        }

        slotsContainer.addEventListener('click', event => {
            if (event.target.matches('.remove-slot-btn')) {
                const row = event.target.closest('.slot-row');
                if (row) {
                    row.remove();
                    refreshSlotNames();
                }
            }
        });

        slotsContainer.addEventListener('change', event => {
            if (event.target.matches('[data-slot-field="meal_type"]') || event.target.matches('[data-slot-field="slot_date"]')) {
                const row = event.target.closest('.slot-row');
                const warning = getDuplicateWarning(row);
                const existingAlert = row.querySelector('.duplicate-warning');
                if (existingAlert) existingAlert.remove();
                if (warning && warning.count > 0) {
                    const alert = document.createElement('div');
                    alert.className = 'duplicate-warning text-warning small mt-1';
                    alert.textContent = '⚠ Ya hay ' + (warning.count + 1) + ' "' + warning.type + '" para esta fecha. ¿Estás seguro?';
                    row.querySelector('.col-md-3')?.after(alert);
                }
            }
        });

        addSlotButton.addEventListener('click', function (e) {
            e.preventDefault();

            const date = this.form?.querySelector('[data-slot-field="slot_date"]')?.value || '';
            const type = this.form?.querySelector('[data-slot-field="meal_type"]')?.value || '';

            let dupCount = 0;
            if (date && type) {
                slotsContainer.querySelectorAll('.slot-row').forEach(row => {
                    if (row.querySelector('[data-slot-field="slot_date"]').value === date &&
                        row.querySelector('[data-slot-field="meal_type"]').value === type) {
                        dupCount++;
                    }
                });
            }

            if (dupCount > 0) {
                if (!confirm('Ya hay ' + dupCount + ' "' + type + '" para el ' + date + '. ¿Agregar de todas formas?')) {
                    return;
                }
            }

            addSlotRow({ slot_date: date, meal_type: type, recipe_id: '' });
        });

        refreshSlotNames();
    })();
    </script>
    @endpush
</x-app-layout>
