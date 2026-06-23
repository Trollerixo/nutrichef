<x-app-layout>
    <div class="d-flex justify-content-between align-items-start mb-4">
        <div>
            <h1 class="fw-bold h2 mb-1">Consultas</h1>
            <p class="text-muted mb-0">Conversaciones con tus pacientes.</p>
        </div>
    </div>

    @if ($consultations->isEmpty())
        <div class="alert alert-secondary">No hay consultas asignadas.</div>
    @else
        @foreach ($consultations as $consultation)
            <a href="{{ route('nutritionist.consultations.show', $consultation) }}" class="text-decoration-none text-dark">
                <div class="card mb-3 border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-3">
                            <div class="flex-shrink-0">
                                <span class="badge bg-light text-dark border rounded-circle d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; font-size: 1.1rem;">
                                    {{ $consultation->patient?->name[0] ?? '?' }}
                                </span>
                            </div>
                            <div class="flex-grow-1 min-w-0">
                                <div class="d-flex justify-content-between align-items-center mb-1">
                                    <h6 class="fw-bold mb-0 text-truncate">{{ $consultation->patient?->name ?? 'Paciente' }}</h6>
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
        @endforeach
    @endif
</x-app-layout>
