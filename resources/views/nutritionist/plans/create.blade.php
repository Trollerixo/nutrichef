<x-app-layout>
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <h1 class="fw-bold h2 mb-1">Nuevo plan semanal</h1>
            <p class="text-muted mb-0">Asigna un menú a uno de tus pacientes.</p>
        </div>
        <a href="{{ route('nutritionist.plans.index') }}" class="btn btn-outline-secondary">Volver</a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('nutritionist.plans.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="patient_id" class="form-label">Paciente</label>
                    <select id="patient_id" name="patient_id" class="form-select @error('patient_id') is-invalid @enderror" required>
                        <option value="">Selecciona un paciente</option>
                        @foreach($patients as $patient)
                            <option value="{{ $patient->id }}" {{ old('patient_id') == $patient->id ? 'selected' : '' }}>{{ $patient->name }} — {{ $patient->email }}</option>
                        @endforeach
                    </select>
                    @error('patient_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label for="title" class="form-label">Título del plan</label>
                        <input id="title" name="title" type="text" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="status" class="form-label">Estado</label>
                        <select id="status" name="status" class="form-select @error('status') is-invalid @enderror">
                            <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Borrador</option>
                            <option value="published" {{ old('status', 'published') == 'published' ? 'selected' : '' }}>Publicado</option>
                        </select>
                        @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="notes" class="form-label">Notas</label>
                    <textarea id="notes" name="notes" rows="3" class="form-control @error('notes') is-invalid @enderror">{{ old('notes') }}</textarea>
                    @error('notes')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <hr>
                <h5 class="mb-3">Recetas por día</h5>

                @foreach(range(0, 2) as $index)
                    <div class="row g-3 align-items-end mb-3">
                        <div class="col-md-4">
                            <label class="form-label">Fecha</label>
                            <input name="slots[{{ $index }}][slot_date]" type="date" class="form-control @error('slots.'.$index.'.slot_date') is-invalid @enderror" value="{{ old('slots.'.$index.'.slot_date') }}">
                            @error('slots.'.$index.'.slot_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Tipo de comida</label>
                            <select name="slots[{{ $index }}][meal_type]" class="form-select @error('slots.'.$index.'.meal_type') is-invalid @enderror">
                                <option value="">Selecciona</option>
                                <option value="desayuno" {{ old('slots.'.$index.'.meal_type') == 'desayuno' ? 'selected' : '' }}>Desayuno</option>
                                <option value="almuerzo" {{ old('slots.'.$index.'.meal_type') == 'almuerzo' ? 'selected' : '' }}>Almuerzo</option>
                                <option value="cena" {{ old('slots.'.$index.'.meal_type') == 'cena' ? 'selected' : '' }}>Cena</option>
                            </select>
                            @error('slots.'.$index.'.meal_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Receta</label>
                            <select name="slots[{{ $index }}][recipe_id]" class="form-select @error('slots.'.$index.'.recipe_id') is-invalid @enderror">
                                <option value="">Selecciona una receta</option>
                                @foreach($recipes as $recipe)
                                    <option value="{{ $recipe->id }}" {{ old('slots.'.$index.'.recipe_id') == $recipe->id ? 'selected' : '' }}>{{ $recipe->title }}</option>
                                @endforeach
                            </select>
                            @error('slots.'.$index.'.recipe_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                @endforeach

                <button type="submit" class="btn btn-dark">Crear plan</button>
            </form>
        </div>
    </div>
</x-app-layout>
