<x-guest-layout>
    <div class="text-center mb-4">
        <h2 class="h5 fw-bold text-prim mb-1">Confirmar contraseña</h2>
        <p class="text-muted mb-0">Por seguridad, confirma tu contraseña</p>
    </div>

    <form method="POST" action="{{ route('password.confirm') }}" novalidate>
        @csrf

        <div class="mb-4">
            <label for="password" class="form-label text-prim fw-semibold">
                <i class="bi bi-lock me-1"></i>Contraseña
            </label>
            <input id="password" type="password" name="password"
                   class="form-control @error('password') is-invalid @enderror"
                   placeholder="Tu contraseña" required autocomplete="current-password">
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-create w-100">
            <i class="bi bi-check2-circle me-2"></i>Confirmar
        </button>
    </form>
</x-guest-layout>
