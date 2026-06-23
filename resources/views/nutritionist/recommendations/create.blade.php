<x-app-layout>
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <h1 class="fw-bold h2 mb-1">Enviar recomendación</h1>
            <p class="text-muted mb-0">Comparte una receta con un paciente.</p>
        </div>
        <a href="{{ route('nutritionist.recommendations.index') }}" class="btn btn-outline-secondary">Volver</a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('nutritionist.recommendations.store') }}" method="POST">
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

                <div class="mb-3">
                    <label for="recipe_id" class="form-label">Receta</label>
                    <select id="recipe_id" name="recipe_id" class="form-select @error('recipe_id') is-invalid @enderror" required>
                        <option value="">Selecciona una receta</option>
                        @foreach($recipes as $recipe)
                            <option value="{{ $recipe->id }}" {{ old('recipe_id') == $recipe->id ? 'selected' : '' }}>{{ $recipe->title }}</option>
                        @endforeach
                    </select>
                    @error('recipe_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="message" class="form-label">Mensaje</label>
                    <textarea id="message" name="message" rows="4" class="form-control @error('message') is-invalid @enderror">{{ old('message') }}</textarea>
                    @error('message')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-dark">Enviar recomendación</button>
            </form>
        </div>
    </div>
</x-app-layout>
