<x-app-layout>
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <h1 class="fw-bold h2 mb-1">Nueva receta</h1>
            <p class="text-muted mb-0">Crea una nueva receta para el sitio.</p>
        </div>
        <a href="{{ route('admin.recetas.index') }}" class="btn btn-outline-secondary">Volver</a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.recetas.store') }}" method="POST" enctype="multipart/form-data" onsubmit="this.querySelector('button[type=submit]').classList.add('btn-loading');">
                @csrf

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="title" class="form-label">Título</label>
                        <input id="title" name="title" type="text" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="slug" class="form-label">Slug</label>
                        <input id="slug" name="slug" type="text" class="form-control @error('slug') is-invalid @enderror" value="{{ old('slug') }}" required>
                        @error('slug')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="category_id" class="form-label">Categoría</label>
                        <select id="category_id" name="category_id" class="form-select @error('category_id') is-invalid @enderror">
                            <option value="">Sin categoría</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="image" class="form-label">Imagen de la receta</label>
                        <input id="image" name="image" type="file" accept="image/*" class="form-control @error('image') is-invalid @enderror">
                        <small class="text-muted">JPG, PNG o WEBP. Máximo 4 MB.</small>
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Descripción</label>
                    <textarea id="description" name="description" rows="4" class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <label for="prep_time_min" class="form-label">Tiempo (minutos)</label>
                        <input id="prep_time_min" name="prep_time_min" type="number" min="0" class="form-control @error('prep_time_min') is-invalid @enderror" value="{{ old('prep_time_min') }}">
                        @error('prep_time_min')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label for="calories" class="form-label">Calorías</label>
                        <input id="calories" name="calories" type="number" min="0" class="form-control @error('calories') is-invalid @enderror" value="{{ old('calories') }}">
                        @error('calories')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label for="featured_date" class="form-label">Fecha destacada</label>
                        <input id="featured_date" name="featured_date" type="date" class="form-control @error('featured_date') is-invalid @enderror" value="{{ old('featured_date') }}">
                        @error('featured_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-3">
                        <label for="proteins_g" class="form-label">Proteínas (g)</label>
                        <input id="proteins_g" name="nutrition[proteins_g]" type="number" step="0.1" min="0" class="form-control @error('nutrition.proteins_g') is-invalid @enderror" value="{{ old('nutrition.proteins_g') }}">
                        @error('nutrition.proteins_g')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-3">
                        <label for="carbs_g" class="form-label">Carbohidratos (g)</label>
                        <input id="carbs_g" name="nutrition[carbs_g]" type="number" step="0.1" min="0" class="form-control @error('nutrition.carbs_g') is-invalid @enderror" value="{{ old('nutrition.carbs_g') }}">
                        @error('nutrition.carbs_g')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-3">
                        <label for="fats_g" class="form-label">Grasas (g)</label>
                        <input id="fats_g" name="nutrition[fats_g]" type="number" step="0.1" min="0" class="form-control @error('nutrition.fats_g') is-invalid @enderror" value="{{ old('nutrition.fats_g') }}">
                        @error('nutrition.fats_g')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-3">
                        <label for="fiber_g" class="form-label">Fibra (g)</label>
                        <input id="fiber_g" name="nutrition[fiber_g]" type="number" step="0.1" min="0" class="form-control @error('nutrition.fiber_g') is-invalid @enderror" value="{{ old('nutrition.fiber_g') }}">
                        @error('nutrition.fiber_g')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-check form-switch mb-4">
                    <input class="form-check-input" type="checkbox" id="published" name="published" value="1" {{ old('published') ? 'checked' : '' }}>
                    <label class="form-check-label" for="published">Publicada</label>
                </div>

                <hr>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">Ingredientes</h5>
                    <button id="addIngredientRow" type="button" class="btn btn-outline-primary btn-sm">
                        <img src="{{ asset('images/icons/añadir_ingredientes.svg') }}" alt="" class="nc-icon-sm me-1"> Agregar ingrediente
                    </button>
                </div>

                @php
                    $ingredientRows = old('ingredients');
                    if ($ingredientRows === null) {
                        $ingredientRows = [['name' => '', 'quantity' => '', 'notes' => '']];
                    }
                @endphp

                <div id="ingredientsContainer">
                    @foreach($ingredientRows as $index => $ingredient)
                        <div class="ingredient-row row g-3 align-items-end mb-2">
                            <div class="col-md-5">
                                <label class="form-label">Ingrediente</label>
                                <input data-ingredient-field="name" name="ingredients[{{ $index }}][name]" type="text" class="form-control" value="{{ $ingredient['name'] ?? '' }}">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Cantidad</label>
                                <input data-ingredient-field="quantity" name="ingredients[{{ $index }}][quantity]" type="text" class="form-control" value="{{ $ingredient['quantity'] ?? '' }}">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">Notas</label>
                                <input data-ingredient-field="notes" name="ingredients[{{ $index }}][notes]" type="text" class="form-control" value="{{ $ingredient['notes'] ?? '' }}">
                            </div>
                            <div class="col-md-1 text-end">
                                <button type="button" class="btn btn-outline-danger btn-sm remove-ingredient-btn">&times;</button>
                            </div>
                        </div>
                    @endforeach
                </div>

                <template id="ingredientRowTemplate">
                    <div class="ingredient-row row g-3 align-items-end mb-2">
                        <div class="col-md-5">
                            <label class="form-label">Ingrediente</label>
                            <input data-ingredient-field="name" type="text" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Cantidad</label>
                            <input data-ingredient-field="quantity" type="text" class="form-control">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Notas</label>
                            <input data-ingredient-field="notes" type="text" class="form-control">
                        </div>
                        <div class="col-md-1 text-end">
                            <button type="button" class="btn btn-outline-danger btn-sm remove-ingredient-btn">&times;</button>
                        </div>
                    </div>
                </template>

                <hr>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">Pasos de preparación</h5>
                    <button id="addStepRow" type="button" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-plus-lg me-1"></i> Agregar paso
                    </button>
                </div>

                @php
                    $stepRows = old('steps');
                    if ($stepRows === null) {
                        $stepRows = [['instruction' => '']];
                    }
                @endphp

                <div id="stepsContainer">
                    @foreach($stepRows as $index => $step)
                        <div class="step-row row g-3 align-items-end mb-3">
                            <div class="col-md-11">
                                <label class="form-label">Paso {{ $index + 1 }}</label>
                                <input data-step-field="instruction" name="steps[{{ $index }}][instruction]" type="text" class="form-control" value="{{ $step['instruction'] ?? '' }}">
                            </div>
                            <div class="col-md-1 text-end">
                                <button type="button" class="btn btn-outline-danger btn-sm remove-step-btn">&times;</button>
                            </div>
                        </div>
                    @endforeach
                </div>

                <template id="stepRowTemplate">
                    <div class="step-row row g-3 align-items-end mb-3">
                        <div class="col-md-11">
                            <label class="form-label">Paso</label>
                            <input data-step-field="instruction" type="text" class="form-control">
                        </div>
                        <div class="col-md-1 text-end">
                            <button type="button" class="btn btn-outline-danger btn-sm remove-step-btn">&times;</button>
                        </div>
                    </div>
                </template>

                <button type="submit" class="btn btn-dark">
                <img src="{{ asset('images/icons/guardar_receta.svg') }}" alt="" class="nc-icon me-1">Guardar receta
                </button>
            </form>
        </div>
    </div>
    @push('scripts')
    <script>
    (function () {
        const ingredientContainer = document.getElementById('ingredientsContainer');
        const addIngredientButton = document.getElementById('addIngredientRow');
        const ingredientTemplate = document.getElementById('ingredientRowTemplate').content.firstElementChild;
        const stepContainer = document.getElementById('stepsContainer');
        const addStepButton = document.getElementById('addStepRow');
        const stepTemplate = document.getElementById('stepRowTemplate').content.firstElementChild;

        function refreshIngredientNames() {
            ingredientContainer.querySelectorAll('.ingredient-row').forEach((row, index) => {
                row.querySelectorAll('[data-ingredient-field]').forEach((field) => {
                    field.name = `ingredients[${index}][${field.dataset.ingredientField}]`;
                });
            });
        }

        function refreshStepNames() {
            stepContainer.querySelectorAll('.step-row').forEach((row, index) => {
                row.querySelectorAll('[data-step-field]').forEach((field) => {
                    field.name = `steps[${index}][${field.dataset.stepField}]`;
                });

                const title = row.querySelector('label');
                if (title) {
                    title.textContent = `Paso ${index + 1}`;
                }
            });
        }

        function addIngredientRow(values = { name: '', quantity: '', notes: '' }) {
            const clone = ingredientTemplate.cloneNode(true);
            clone.querySelectorAll('[data-ingredient-field]').forEach((field) => {
                field.value = values[field.dataset.ingredientField] || '';
            });
            ingredientContainer.appendChild(clone);
            refreshIngredientNames();
        }

        function addStepRow(values = { instruction: '' }) {
            const clone = stepTemplate.cloneNode(true);
            clone.querySelectorAll('[data-step-field]').forEach((field) => {
                field.value = values[field.dataset.stepField] || '';
            });
            stepContainer.appendChild(clone);
            refreshStepNames();
        }

        ingredientContainer.addEventListener('click', (event) => {
            if (event.target.matches('.remove-ingredient-btn')) {
                const row = event.target.closest('.ingredient-row');
                if (row) {
                    row.remove();
                    refreshIngredientNames();
                }
            }
        });

        stepContainer.addEventListener('click', (event) => {
            if (event.target.matches('.remove-step-btn')) {
                const row = event.target.closest('.step-row');
                if (row) {
                    row.remove();
                    refreshStepNames();
                }
            }
        });

        addIngredientButton.addEventListener('click', (event) => {
            event.preventDefault();
            addIngredientRow();
        });

        addStepButton.addEventListener('click', (event) => {
            event.preventDefault();
            addStepRow();
        });

        refreshIngredientNames();
        refreshStepNames();
    })();
    </script>
    @endpush
</x-app-layout>
