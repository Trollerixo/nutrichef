<x-app-layout>
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="fw-bold h2 mb-0">Nuevo nutricionista</h1>
            <p class="text-muted mb-0">Crea un nuevo perfil de nutricionista para el sistema.</p>
        </div>
        <a href="{{ route('admin.nutricionistas.index') }}" class="btn btn-outline-secondary">Volver</a>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.nutricionistas.store') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label" for="name">Nombre</label>
                    <input id="name" name="name" type="text" class="form-control" value="{{ old('name') }}" required>
                    <x-input-error :messages="$errors->get('name')" class="mt-2" />
                </div>

                <div class="mb-3">
                    <label class="form-label" for="email">Email</label>
                    <input id="email" name="email" type="email" class="form-control" value="{{ old('email') }}" required>
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div class="mb-3">
                    <label class="form-label" for="specialty">Especialidad</label>
                    <input id="specialty" name="specialty" type="text" class="form-control" value="{{ old('specialty') }}">
                    <x-input-error :messages="$errors->get('specialty')" class="mt-2" />
                </div>

                <div class="mb-3">
                    <label class="form-label" for="password">Contraseña</label>
                    <input id="password" name="password" type="password" class="form-control" required>
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <div class="mb-3">
                    <label class="form-label" for="password_confirmation">Confirmar contraseña</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" class="form-control" required>
                </div>

                <div class="form-check mb-4">
                    <input class="form-check-input" type="checkbox" id="active" name="active" value="1" @checked(old('active'))>
                    <label class="form-check-label" for="active">Activo</label>
                </div>

                <button type="submit" class="btn btn-dark">Crear nutricionista</button>
            </form>
        </div>
    </div>
</x-app-layout>
