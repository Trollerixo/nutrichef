<x-app-layout>
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <h1 class="fw-bold h2 mb-1">Nueva consulta</h1>
            <p class="text-muted mb-0">Envía tu consulta a un nutricionista asignado.</p>
        </div>
        <a href="{{ route('messages.index') }}" class="btn btn-outline-secondary">Volver</a>
    </div>

    <div class="card">
        <div class="card-body">
            @if ($nutritionists->isEmpty())
                <div class="alert alert-secondary">
                    No tienes nutricionistas asignados. Contacta con el equipo para que te asignen uno.
                </div>
            @else
                <form action="{{ route('consultations.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="nutritionist_id" class="form-label">Nutricionista</label>
                        <select id="nutritionist_id" name="nutritionist_id" class="form-select @error('nutritionist_id') is-invalid @enderror" required>
                            <option value="">Selecciona un nutricionista</option>
                            @foreach ($nutritionists as $nutritionist)
                                <option value="{{ $nutritionist->id }}" {{ old('nutritionist_id') == $nutritionist->id ? 'selected' : '' }}>{{ $nutritionist->name }} — {{ $nutritionist->email }}</option>
                            @endforeach
                        </select>
                        @error('nutritionist_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="subject" class="form-label">Asunto</label>
                        <input id="subject" name="subject" type="text" class="form-control @error('subject') is-invalid @enderror" value="{{ old('subject') }}" required>
                        @error('subject')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="body" class="form-label">Mensaje</label>
                        <textarea id="body" name="body" rows="5" class="form-control @error('body') is-invalid @enderror" required>{{ old('body') }}</textarea>
                        @error('body')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-dark">Enviar consulta</button>
                </form>
            @endif
        </div>
    </div>
</x-app-layout>
