<div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-hidden="true" x-data="{
    title: '¿Estás seguro?',
    message: 'Esta acción no se puede deshacer.',
    action: '',
    submit() {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = this.action;

        const csrf = document.querySelector('meta[name=csrf-token]').content;
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = csrf;

        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'DELETE';

        form.appendChild(csrfInput);
        form.appendChild(methodInput);
        document.body.appendChild(form);
        form.submit();
    }
}" @confirm-delete.window="title = $event.detail.title || title; message = $event.detail.message || message; action = $event.detail.action; new bootstrap.Modal($el).show();">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold" x-text="title"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body py-4">
                <p class="text-muted mb-0" x-text="message"></p>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-light btn-sm px-3" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger btn-sm px-4" @click="submit()">
                    <span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true" x-show="false"></span>
                    Confirmar Eliminación
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    function triggerDelete(action, title, message) {
        window.dispatchEvent(new CustomEvent('confirm-delete', {
            detail: { action, title, message }
        }));
    }
</script>
