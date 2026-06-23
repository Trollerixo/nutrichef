<x-app-layout>
    @php
        $chatPatient = $consultation->patient;
        $chatPatientName = $chatPatient?->name ?? 'Paciente';
        $chatIsOnline = $chatPatient?->isOnline() ?? false;
        $chatLastSeen = $chatPatient?->lastSeen() ?? '';
    @endphp

    <script>
        window.chatNutritionist = (consultationId, online, lastSeen) => ({
            consultationId,
            messageBody: '',
            online,
            lastSeen,
            statusText: '',

            init() {
                this.statusText = this.online ? 'En l\u00ednea' : this.lastSeen || 'Desconectado';
                this.$nextTick(() => this.scrollToBottom());
                this.startPolling();
            },

            startPolling() {
                setInterval(() => {
                    fetch(`/nutricionista/consultas/${this.consultationId}/online`)
                        .then(r => r.json())
                        .then(data => {
                            this.online = data.online;
                            this.lastSeen = data.last_seen;
                            this.statusText = this.online
                                ? 'En l\u00ednea'
                                : this.lastSeen || 'Desconectado';
                        });
                }, 30000);
            },

            scrollToBottom() {
                const el = this.$refs.messages;
                if (el) requestAnimationFrame(() => el.scrollTop = el.scrollHeight);
            },

            async sendMessage() {
                const body = this.messageBody.trim();
                if (!body) return;

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

                    if (data.success) {
                        this.messageBody = '';
                        this.appendMessage(data.message);
                    }
                } catch (e) {
                    console.error('Error al enviar mensaje', e);
                }
            },

            appendMessage(msg) {
                const container = this.$refs.messages;
                const div = document.createElement('div');
                div.className = 'chat-message chat-message--mine';
                div.innerHTML = `
                    <div class="chat-bubble">
                        <p class="mb-1">${this.escapeHtml(msg.body)}</p>
                        <span class="chat-time">${msg.sent_at}</span>
                    </div>
                `;
                container.appendChild(div);
                this.scrollToBottom();
            },

            escapeHtml(text) {
                const d = document.createElement('div');
                d.textContent = text;
                return d.innerHTML;
            },
        });
    </script>

    <div class="chat-container"
         x-data="chatNutritionist(
             {{ $consultation->id }},
             {{ $chatIsOnline ? 'true' : 'false' }},
             @js($chatLastSeen)
         )">

        {{-- Header --}}
        <div class="chat-header">
            <a href="{{ route('nutritionist.consultations.index') }}" class="btn btn-link text-white p-0 me-2">
                <i class="bi bi-arrow-left fs-5"></i>
            </a>
            <div class="flex-grow-1 min-w-0">
                <h6 class="fw-bold mb-0 text-truncate">{{ $chatPatientName }}</h6>
                <small class="text-white-50" x-text="statusText">Cargando...</small>
            </div>
        </div>

        {{-- Messages --}}
        <div class="chat-messages" x-ref="messages">
            @foreach ($consultation->messages as $message)
                <div class="chat-message {{ $message->sender_id === auth()->id() ? 'chat-message--mine' : 'chat-message--theirs' }}">
                    <div class="chat-bubble">
                        <p class="mb-1">{{ $message->body }}</p>
                        <span class="chat-time">{{ $message->sent_at->format('H:i') }}</span>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Input --}}
        <div class="chat-input">
            <form x-ref="form" @submit.prevent="sendMessage">
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
