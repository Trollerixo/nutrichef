<x-app-layout>
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <h1 class="fw-bold h2 mb-1">Editar receta</h1>
            <p class="text-muted mb-0">Actualiza los datos de la receta.</p>
        </div>
        <a href="{{ route('admin.recetas.index') }}" class="btn btn-outline-secondary">Volver</a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.recetas.update', $recipe) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="title" class="form-label">Título</label>
                        <input id="title" name="title" type="text" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $recipe->title) }}" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="slug" class="form-label">Slug</label>
                        <input id="slug" name="slug" type="text" class="form-control @error('slug') is-invalid @enderror" value="{{ old('slug', $recipe->slug) }}" required>
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
                                <option value="{{ $category->id }}" {{ old('category_id', $recipe->category_id) == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div
                        class="col-md-6"
                        x-data="{
                            previewSrc: @js($recipe->image_url),
                            objectUrl: null,
                            updatePreview(event) {
                                const file = event.target.files && event.target.files[0];

                                if (!file) {
                                    if (this.objectUrl) {
                                        URL.revokeObjectURL(this.objectUrl);
                                        this.objectUrl = null;
                                    }

                                    this.previewSrc = @js($recipe->image_url);
                                    return;
                                }

                                if (this.objectUrl) {
                                    URL.revokeObjectURL(this.objectUrl);
                                }

                                this.objectUrl = URL.createObjectURL(file);
                                this.previewSrc = this.objectUrl;
                            }
                        }"
                    >
                        <label for="image" class="form-label">Imagen de la receta</label>
                        <input
                            id="image"
                            name="image"
                            type="file"
                            accept="image/*"
                            class="form-control @error('image') is-invalid @enderror"
                            x-on:change="updatePreview($event)"
                        >
                        <small class="text-muted">Sube una nueva imagen para reemplazar la actual.</small>
                        @error('image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="mt-3">
                            <img
                                id="recipeImagePreview"
                                x-bind:src="previewSrc"
                                class="img-fluid rounded border"
                                x-bind:class="{ 'd-none': !previewSrc }"
                                alt="{{ $recipe->title }}"
                                style="max-height: 180px; object-fit: cover;"
                            >
                            <div id="recipeImagePlaceholder" x-show="!previewSrc" class="text-muted small">
                                No hay imagen actual.
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Descripción</label>
                    <textarea id="description" name="description" rows="4" class="form-control @error('description') is-invalid @enderror">{{ old('description', $recipe->description) }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <label for="prep_time_min" class="form-label">Tiempo (minutos)</label>
                        <input id="prep_time_min" name="prep_time_min" type="number" min="0" class="form-control @error('prep_time_min') is-invalid @enderror" value="{{ old('prep_time_min', $recipe->prep_time_min) }}">
                        @error('prep_time_min')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label for="calories" class="form-label">Calorías</label>
                        <input id="calories" name="calories" type="number" min="0" class="form-control @error('calories') is-invalid @enderror" value="{{ old('calories', $recipe->calories) }}">
                        @error('calories')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-4">
                        <label for="featured_date" class="form-label">Fecha destacada</label>
                        <input id="featured_date" name="featured_date" type="date" class="form-control @error('featured_date') is-invalid @enderror" value="{{ old('featured_date', optional($recipe->featured_date)->format('Y-m-d')) }}">
                        @error('featured_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-check form-switch mb-4">
                    <input class="form-check-input" type="checkbox" id="published" name="published" value="1" {{ old('published', $recipe->published) ? 'checked' : '' }}>
                    <label class="form-check-label" for="published">Publicada</label>
                </div>

                <hr>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h5 class="mb-0">Ingredientes</h5>
                    <button id="addIngredientRow" type="button" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-plus-lg me-1"></i> Agregar ingrediente
                    </button>
                </div>

                @php
                    $ingredientRows = old('ingredients');
                    if ($ingredientRows === null) {
                        $ingredientRows = $recipe->ingredients->map(function ($ingredient) {
                            return [
                                'name' => $ingredient->name,
                                'quantity' => $ingredient->pivot->quantity,
                                'notes' => $ingredient->pivot->notes,
                            ];
                        })->toArray();
                    }

                    if (empty($ingredientRows)) {
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
                        $stepRows = $recipe->steps->map(function ($step) {
                            return ['instruction' => $step->instruction];
                        })->toArray();
                    }

                    if (empty($stepRows)) {
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

                <button type="submit" class="btn btn-dark">Guardar cambios</button>
            </form>
        </div>
    </div>
</x-app-layout>
