<x-guest-layout>
    <div class="text-center mb-4">
        <h2 class="h5 fw-bold text-prim mb-1">Bienvenido</h2>
        <p class="text-muted mb-0">Inicia sesión para continuar</p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-3" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" novalidate>
        @csrf

        <!-- Email Address -->
        <div class="mb-3">
            <label for="email" class="form-label text-prim fw-semibold">
                <i class="bi bi-envelope me-1"></i>Correo electrónico
            </label>
            <input id="email" type="email" name="email" value="{{ old('email') }}"
                   class="form-control @error('email') is-invalid @enderror"
                   placeholder="usuario@empresa.com"
                   required autofocus autocomplete="username">
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Password -->
        <div class="mb-3">
            <label for="password" class="form-label text-prim fw-semibold">
                <i class="bi bi-lock me-1"></i>Contraseña
            </label>
            <div class="input-group">
                <input id="password" type="password" name="password"
                       class="form-control @error('password') is-invalid @enderror"
                       placeholder="Tu contraseña"
                       required autocomplete="current-password">
                <button class="btn btn-outline-secondary" type="button" id="togglePassword" aria-label="Mostrar u ocultar contraseña">
                    <i class="bi bi-eye"></i>
                </button>
                @error('password')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" id="remember_me" name="remember">
                <label class="form-check-label text-muted" for="remember_me">
                    Recordarme
                </label>
            </div>

            @if (Route::has('password.request'))
                <a class="text-decoration-none small text-prim" href="{{ route('password.request') }}">
                    ¿Olvidaste tu contraseña?
                </a>
            @endif
        </div>

        <button type="submit" class="btn btn-create w-100">
            <i class="bi bi-box-arrow-in-right me-2"></i>Ingresar
        </button>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const toggle = document.getElementById('togglePassword');
            const input = document.getElementById('password');

            if (toggle && input) {
                toggle.addEventListener('click', function () {
                    const isPassword = input.getAttribute('type') === 'password';
                    input.setAttribute('type', isPassword ? 'text' : 'password');
                    this.innerHTML = isPassword ? '<i class="bi bi-eye-slash"></i>' : '<i class="bi bi-eye"></i>';
                });
            }
        });
    </script>
</x-guest-layout>
