<x-guest-layout>
    <div class="text-center mb-4">
        <h2 class="h5 fw-bold text-prim mb-1">Restablecer contraseña</h2>
        <p class="text-muted mb-0">Crea una nueva contraseña segura</p>
    </div>

    <form method="POST" action="{{ route('password.store') }}" novalidate>
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div class="mb-3">
            <label for="email" class="form-label text-prim fw-semibold">
                <i class="bi bi-envelope me-1"></i>Correo electrónico
            </label>
            <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}"
                   class="form-control @error('email') is-invalid @enderror"
                   required autofocus autocomplete="username" readonly>
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password" class="form-label text-prim fw-semibold">
                <i class="bi bi-lock me-1"></i>Nueva contraseña
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

        <button type="submit" class="btn btn-create w-100">
            <i class="bi bi-shield-lock me-2"></i>Restablecer
        </button>
    </form>
</x-guest-layout>
