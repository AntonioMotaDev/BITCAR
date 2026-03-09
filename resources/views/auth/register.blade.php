<x-guest-layout>
    <div class="text-center mb-4">
        <h2 class="h5 fw-bold text-prim mb-1">Crear cuenta</h2>
        <p class="text-muted mb-0">Completa tus datos para registrarte</p>
    </div>

    <form method="POST" action="{{ route('register') }}" novalidate>
        @csrf

        <div class="mb-3">
            <label for="name" class="form-label text-prim fw-semibold">
                <i class="bi bi-person me-1"></i>Nombre completo
            </label>
            <input id="name" type="text" name="name" value="{{ old('name') }}"
                   class="form-control @error('name') is-invalid @enderror"
                   placeholder="Ej: Juan Pérez" required autofocus autocomplete="name">
            @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="email" class="form-label text-prim fw-semibold">
                <i class="bi bi-envelope me-1"></i>Correo electrónico
            </label>
            <input id="email" type="email" name="email" value="{{ old('email') }}"
                   class="form-control @error('email') is-invalid @enderror"
                   placeholder="usuario@empresa.com" required autocomplete="username">
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password" class="form-label text-prim fw-semibold">
                <i class="bi bi-lock me-1"></i>Contraseña
            </label>
            <input id="password" type="password" name="password"
                   class="form-control @error('password') is-invalid @enderror"
                   placeholder="Mínimo 8 caracteres" required autocomplete="new-password">
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-4">
            <label for="password_confirmation" class="form-label text-prim fw-semibold">
                <i class="bi bi-lock-fill me-1"></i>Confirmar contraseña
            </label>
            <input id="password_confirmation" type="password" name="password_confirmation"
                   class="form-control @error('password_confirmation') is-invalid @enderror"
                   placeholder="Repite la contraseña" required autocomplete="new-password">
            @error('password_confirmation')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="d-flex justify-content-between align-items-center">
            <a class="text-decoration-none small text-prim" href="{{ route('login') }}">
                Ya tengo cuenta
            </a>
            <button type="submit" class="btn btn-create">
                <i class="bi bi-person-plus me-2"></i>Registrarme
            </button>
        </div>
    </form>
</x-guest-layout>
