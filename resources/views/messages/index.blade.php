<x-app-layout>
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <h1 class="fw-bold h2 mb-1">Mensajes</h1>
            <p class="text-muted mb-0">Tus conversaciones con nutricionistas.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Inline new consultation form --}}
    <div x-data="{ showForm: false }"
         @toggle-consultation-form.window="showForm = !showForm"
         class="mb-4">
        @if ($consultations->isNotEmpty())
            <button class="btn btn-dark btn-sm mb-3" @click="showForm = true" x-show="!showForm">
                <i class="bi bi-plus-lg me-1"></i> Nueva consulta
            </button>
        @endif

        <div x-show="showForm" x-cloak class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold mb-0"><i class="bi bi-plus-circle me-2"></i>Nueva consulta</h6>
                    <button type="button" class="btn-close btn-close-sm" @click="showForm = false"></button>
                </div>

                @if ($nutritionists->isEmpty())
                    <div class="alert alert-secondary mb-0">
                        No tienes nutricionistas asignados. Contacta con el equipo para que te asignen uno.
                    </div>
                @else
                    <form action="{{ route('consultations.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="nutritionist_id" class="form-label small fw-semibold">Nutricionista</label>
                            <select id="nutritionist_id" name="nutritionist_id" class="form-select @error('nutritionist_id') is-invalid @enderror" required>
                                <option value="">Selecciona un nutricionista</option>
                                @foreach ($nutritionists as $nutritionist)
                                    <option value="{{ $nutritionist->id }}">{{ $nutritionist->name }}</option>
                                @endforeach
                            </select>
                            @error('nutritionist_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="subject" class="form-label small fw-semibold">Asunto</label>
                            <input id="subject" name="subject" type="text" class="form-control @error('subject') is-invalid @enderror" value="{{ old('subject') }}" placeholder="Ej: Consulta sobre mi plan alimenticio" required>
                            @error('subject')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="body" class="form-label small fw-semibold">Mensaje</label>
                            <textarea id="body" name="body" rows="4" class="form-control @error('body') is-invalid @enderror" placeholder="Escribe tu consulta..." required>{{ old('body') }}</textarea>
                            @error('body')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-dark">Enviar consulta</button>
                            <button type="button" class="btn btn-outline-secondary btn-sm" @click="showForm = false">Cancelar</button>
                        </div>
                    </form>
                @endif
            </div>
        </div>
    </div>

    {{-- Consultation list (one per nutritionist) --}}
    @forelse ($consultations as $consultation)
        <a href="{{ route('consultations.show', $consultation) }}" class="text-decoration-none text-dark">
            <div class="card mb-3 border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-3">
                        <div class="flex-shrink-0">
                            <span class="badge bg-light text-dark border rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; font-size: 1.1rem;">
                                {{ $consultation->nutritionist?->name[0] ?? '?' }}
                            </span>
                        </div>
                        <div class="flex-grow-1 min-w-0">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <h6 class="fw-bold mb-0 text-truncate">{{ $consultation->nutritionist?->name ?? 'Nutricionista' }}</h6>
                                @if ($consultation->messages->isNotEmpty())
                                    <small class="text-muted flex-shrink-0 ms-2">{{ $consultation->messages->last()->sent_at->diffForHumans() }}</small>
                                @endif
                            </div>
                            @php
                                $last = $consultation->messages->last();
                            @endphp
                            <p class="mb-0 small text-muted text-truncate">
                                @if ($last)
                                    <strong>{{ $last->sender_id === auth()->id() ? 'Tú' : ($last->sender?->name ?? '') }}:</strong>
                                    {{ $last->body }}
                                @else
                                    Sin mensajes
                                @endif
                            </p>
                        </div>
                        <i class="bi bi-chevron-right text-muted flex-shrink-0"></i>
                    </div>
                </div>
            </div>
        </a>
    @empty
        <div class="text-center py-5">
            <i class="bi bi-chat-square-text fs-1 text-muted d-block mb-3"></i>
            <h5 class="fw-bold text-muted">No tienes conversaciones</h5>
            <p class="text-muted mb-4">Crea una nueva consulta para comunicarte con tu nutricionista.</p>
            <button class="btn btn-dark" x-data @click="window.dispatchEvent(new CustomEvent('toggle-consultation-form'))">
                <i class="bi bi-plus-lg me-1"></i> Nueva consulta
            </button>
        </div>
    @endforelse
</x-app-layout>
