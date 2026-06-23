<h4 class="fw-semibold mb-2 text-danger">Eliminar cuenta</h4>
<p class="text-muted small mb-4">
    Una vez eliminada, todos tus datos serán borrados permanentemente.
</p>

<button type="button" class="btn btn-danger btn-sm"
        data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
    Eliminar cuenta
</button>

<div class="modal fade" id="deleteAccountModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">¿Eliminar tu cuenta?</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted small">Esta acción es irreversible. Confirma tu contraseña para continuar.</p>
                <form method="POST" action="{{ route('profile.destroy') }}" id="deleteAccountForm">
                    @csrf
                    @method('DELETE')
                    <x-input-label for="password_delete" value="Contraseña actual" />
                    <x-text-input id="password_delete" name="password" type="password" />
                    <x-input-error :messages="$errors->userDeletion->get('password')" />
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm"
                        data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger btn-sm"
                        onclick="document.getElementById('deleteAccountForm').submit()">
                    Eliminar definitivamente
                </button>
            </div>
        </div>
    </div>
</div>
