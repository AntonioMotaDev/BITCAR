<x-guest-layout>
    <div class="text-center mb-4">
        <h2 class="h5 fw-bold text-prim mb-1">Recuperar contraseña</h2>
        <p class="text-muted mb-0">Te enviaremos un enlace de restablecimiento</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-3" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" novalidate>
        @csrf

        <!-- Email Address -->
        <div class="mb-3">
            <label for="email" class="form-label text-prim fw-semibold">
                <i class="bi bi-envelope me-1"></i>Correo electrónico
            </label>
            <input id="email" type="email" name="email" value="{{ old('email') }}"
                   class="form-control @error('email') is-invalid @enderror"
                   placeholder="usuario@empresa.com" required autofocus>
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-create w-100">
            <i class="bi bi-send me-2"></i>Enviar enlace
        </button>
        <div class="text-center mt-3">
            <a class="text-decoration-none small text-prim" href="{{ route('login') }}">
                Volver a iniciar sesión
            </a>
        </div>
    </form>
</x-guest-layout>
