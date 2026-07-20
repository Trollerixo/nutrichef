<x-app-layout>
    @php
        $chatPatient = $consultation->patient;
        $chatPatientName = $chatPatient?->name ?? 'Paciente';
        $chatIsOnline = $chatPatient?->isOnline() ?? false;
        $chatLastSeen = $chatPatient?->lastSeen() ?? '';
        $initialMessages = $consultation->messages->map(fn($m) => [
            'id' => $m->id,
            'body' => $m->body,
            'sender_id' => $m->sender_id,
            'sent_at' => $m->sent_at?->format('H:i'),
            'is_mine' => $m->sender_id === auth()->id(),
        ])->values();
    @endphp

    <script>
        window.chatNutritionist = (consultationId, online, lastSeen, status, initialMessages) => ({
            consultationId,
            messageBody: '',
            online,
            lastSeen,
            statusText: '',
            consultationStatus: status,
            messages: initialMessages || [],

            init() {
                this.updateStatusText();
                this.$nextTick(() => this.scrollToBottom());
                this.startPolling();
            },

            updateStatusText() {
                if (this.consultationStatus === 'closed') {
                    this.statusText = 'Consulta finalizada';
                } else {
                    this.statusText = this.online ? 'En l\u00ednea' : (this.lastSeen || 'Desconectado');
                }
            },

            startPolling() {
                setInterval(() => {
                    fetch(`/nutricionista/consultas/${this.consultationId}/online`)
                        .then(r => r.json())
                        .then(data => {
                            this.online = data.online;
                            this.lastSeen = data.last_seen;
                            if (data.status) this.consultationStatus = data.status;
                            this.updateStatusText();

                            if (data.messages && Array.isArray(data.messages)) {
                                const prevLength = this.messages.length;
                                const lastId = prevLength > 0 ? this.messages[prevLength - 1].id : null;
                                const newLastId = data.messages.length > 0 ? data.messages[data.messages.length - 1].id : null;

                                this.messages = data.messages;

                                if (data.messages.length > prevLength || lastId !== newLastId) {
                                    this.$nextTick(() => this.scrollToBottom());
                                }
                            }
                        });
                }, 3000);
            },

            scrollToBottom() {
                const el = this.$refs.messages;
                if (el) requestAnimationFrame(() => el.scrollTop = el.scrollHeight);
            },

            async sendMessage() {
                const body = this.messageBody.trim();
                if (!body || this.consultationStatus === 'closed') return;

                const token = document.querySelector('[name=csrf-token]')?.content;

                try {
                    const res = await fetch(`/nutricionista/consultas/${this.consultationId}/mensajes`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': token,
                        },
                        body: JSON.stringify({ body }),
                    });

                    const data = await res.json();

                    if (data.success && data.message) {
                        this.messageBody = '';
                        if (!this.messages.some(m => m.id === data.message.id)) {
                            this.messages.push(data.message);
                        }
                        this.$nextTick(() => this.scrollToBottom());
                    }
                } catch (e) {
                    console.error('Error al enviar mensaje', e);
                }
            },

            async closeConsultation() {
                if (!confirm('\u00bfEst\u00e1s seguro de finalizar esta consulta?')) return;
                const token = document.querySelector('[name=csrf-token]')?.content;
                try {
                    const res = await fetch(`/nutricionista/consultas/${this.consultationId}/cerrar`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': token,
                        },
                    });
                    const data = await res.json();
                    if (data.success) {
                        this.consultationStatus = 'closed';
                        this.updateStatusText();
                    }
                } catch (e) {
                    console.error('Error al cerrar consulta', e);
                }
            },
        });
    </script>

    <div class="chat-container"
         x-data="chatNutritionist(
             {{ $consultation->id }},
             {{ $chatIsOnline ? 'true' : 'false' }},
             @js($chatLastSeen),
             @js($consultation->status),
             @js($initialMessages)
         )">

        {{-- Header --}}
        <div class="chat-header d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center min-w-0">
                <a href="{{ route('nutritionist.consultations.index') }}" class="btn btn-link text-white p-0 me-2">
                    <i class="bi bi-arrow-left fs-5"></i>
                </a>
                <div class="min-w-0">
                    <h6 class="fw-bold mb-0 text-truncate">{{ $chatPatientName }}</h6>
                    <small class="text-white-50" x-text="statusText">Cargando...</small>
                </div>
            </div>
            <div>
                <template x-if="consultationStatus !== 'closed'">
                    <button type="button" @click="closeConsultation()" class="btn btn-sm btn-outline-light text-nowrap">
                        <i class="bi bi-check-circle me-1"></i> Finalizar consulta
                    </button>
                </template>
                <template x-if="consultationStatus === 'closed'">
                    <span class="badge bg-secondary">Finalizada</span>
                </template>
            </div>
        </div>

        {{-- Messages --}}
        <div class="chat-messages" x-ref="messages">
            <template x-for="message in messages" :key="message.id">
                <div class="chat-message" :class="message.is_mine ? 'chat-message--mine' : 'chat-message--theirs'">
                    <div class="chat-bubble">
                        <p class="mb-1" x-text="message.body"></p>
                        <span class="chat-time" x-text="message.sent_at"></span>
                    </div>
                </div>
            </template>
        </div>

        {{-- Input --}}
        <div class="chat-input">
            <div x-show="consultationStatus === 'closed'" class="text-center text-muted py-2 small">
                <i class="bi bi-lock me-1"></i> Has finalizado esta consulta.
            </div>
            <form x-show="consultationStatus !== 'closed'" x-ref="form" @submit.prevent="sendMessage">
                <div class="d-flex gap-2">
                    <input type="text" name="body" x-model="messageBody" class="form-control" placeholder="Escribe un mensaje..." required autocomplete="off">
                    <button type="submit" class="btn btn-dark" :disabled="!messageBody.trim()">
                        <i class="bi bi-send"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
