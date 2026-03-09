<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'BITCAR') }} - Acceso</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

        <!-- Bootstrap Icons -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased" style="background-color: var(--color-background);">
        <div class="min-vh-100 d-flex flex-column justify-content-center align-items-center py-5">
            <div class="mb-4 text-center">
                <a href="/" class="d-inline-flex align-items-center gap-2 text-decoration-none">
                    <x-application-logo class="w-20 h-20 fill-current text-gray-500" />
                </a>
                <div class="mt-2">
                    <h1 class="h4 fw-bold text-prim mb-1">BITCAR</h1>
                    <p class="text-muted mb-0">Control Vehicular</p>
                </div>
            </div>

            <div class="w-100" style="max-width: 420px;">
                <div class="card card-custom border-0 shadow-sm">
                    <div class="card-body p-4">
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </div>

        <!-- Bootstrap Bundle JS (incluye Popper) -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    </body>
</html>
