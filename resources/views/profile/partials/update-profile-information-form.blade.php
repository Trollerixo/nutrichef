<h4 class="fw-semibold mb-4">
    <img src="{{ asset('images/icons/editar_perfil_de_usuario.svg') }}" alt="" class="nc-icon me-2">Datos personales
</h4>

<form method="POST" action="{{ route('profile.update') }}">
    @csrf
    @method('PATCH')

    <div class="row g-3 mb-3">
        <div class="col-md-6">
            <x-input-label for="name" value="Nombre" />
            <x-text-input id="name" name="name" type="text"
                :value="old('name', $user->name)" required autofocus />
            <x-input-error :messages="$errors->get('name')" />
        </div>
        <div class="col-md-6">
            <x-input-label for="email" value="Correo" />
            <x-text-input id="email" name="email" type="email"
                :value="old('email', $user->email)" required />
            <x-input-error :messages="$errors->get('email')" />
        </div>
    </div>

    @if (session('status') === 'profile-updated')
        <div class="alert alert-success mb-3">Perfil actualizado correctamente.</div>
    @endif

    <x-primary-button>
        <img src="{{ asset('images/icons/guardar_datos_de_usuario.svg') }}" alt="" class="nc-icon me-1">Guardar cambios
    </x-primary-button>
</form>
