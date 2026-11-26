<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-100">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Gestión de Tareas') }} - @yield('title', 'Sistema de Tareas')</title>

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    @livewireStyles
    @yield('styles')
</head>
<body class="d-flex flex-column h-100 bg-light">
    <!-- Navbar Bootstrap 5 -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold d-flex align-items-center" href="{{ route('tasks.index') }}">
                <i class="bi bi-check2-square text-primary fs-4 me-2"></i>
                <span>Gestión de Tareas</span>
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('tasks.*') ? 'active fw-semibold' : '' }}" href="{{ route('tasks.index') }}">
                            <i class="bi bi-list-task me-1"></i> Tareas
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('products.*') ? 'active fw-semibold' : '' }}" href="{{ route('products.index') }}">
                            <i class="bi bi-box-seam me-1"></i> Productos
                        </a>
                    </li>
                </ul>

                <div class="d-flex">
                    <a href="{{ route('tasks.create') }}" class="btn btn-primary d-flex align-items-center">
                        <i class="bi bi-plus-circle me-2"></i> Nueva Tarea
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Contenido Principal -->
    <main class="flex-shrink-0 py-4">
        <div class="container">
            <!-- Mensaje de éxito flash global (para vistas no Livewire) -->
            @if (session('success') && !request()->routeIs('tasks.index'))
                <div class="alert alert-success alert-dismissible fade show d-flex align-items-center mb-4 shadow-sm" role="alert">
                    <i class="bi bi-check-circle-fill me-2 fs-5"></i>
                    <div>{{ session('success') }}</div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Errores de validación globales -->
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show mb-4 shadow-sm" role="alert">
                    <div class="d-flex align-items-center mb-2 fw-semibold">
                        <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
                        <span>Por favor corrige los siguientes errores:</span>
                    </div>
                    <ul class="mb-0 ps-3">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </main>

    <!-- Footer Bootstrap 5 -->
    <footer class="footer mt-auto py-3 bg-white border-top text-center text-secondary small">
        <div class="container">
            <span>Sistema de Gestión de Tareas &bull; Laravel {{ app()->version() }} &bull; Livewire 3 &bull; Bootstrap 5</span>
        </div>
    </footer>

    <!-- Bootstrap 5 Bundle JS (incluye Popper) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    @livewireScripts
    @yield('scripts')
</body>
</html>
