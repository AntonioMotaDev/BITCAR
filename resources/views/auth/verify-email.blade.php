<x-guest-layout>
    <div class="text-center mb-4">
        <h2 class="h5 fw-bold text-prim mb-1">Verifica tu correo</h2>
        <p class="text-muted mb-0">Te enviamos un enlace para activar tu cuenta</p>
    </div>

    <div class="alert alert-light border d-flex align-items-start gap-2">
        <i class="bi bi-envelope-check text-prim mt-1"></i>
        <div class="small text-muted">
            {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
        </div>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="alert alert-success d-flex align-items-center" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            <div class="small">
                {{ __('A new verification link has been sent to the email address you provided during registration.') }}
            </div>
        </div>
    @endif

    <div class="d-flex flex-column gap-2">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf

            <button type="submit" class="btn btn-create w-100">
                <i class="bi bi-send me-2"></i>Reenviar verificación
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button type="submit" class="btn btn-cancel w-100">
                <i class="bi bi-box-arrow-left me-2"></i>Cerrar sesión
            </button>
        </form>
    </div>
</x-guest-layout>
