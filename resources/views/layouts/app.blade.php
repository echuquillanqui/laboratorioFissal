<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laboratorio Fissal') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Inter:400,500,600,700,800" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="app-shell">
    <div id="app" class="min-vh-100 d-flex flex-column">
        <nav class="navbar navbar-expand-lg navbar-dark app-navbar sticky-top">
            <div class="container-fluid px-3 px-lg-5">
                <a class="navbar-brand brand-mark" href="{{ url('/') }}">
                    <span class="brand-icon">LF</span>
                    <span>
                        <span class="brand-title">{{ config('app.name', 'Laboratorio Fissal') }}</span>
                        <span class="brand-subtitle">Gestión clínica</span>
                    </span>
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto main-nav gap-lg-2 mt-3 mt-lg-0">
                        @auth
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" aria-current="page" href="{{ route('home') }}">Panel</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('users.index') ? 'active' : '' }}" href="{{ route('users.index') }}">Usuarios</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#">Pacientes</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#">Resultados</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#">Reportes</a>
                            </li>
                        @endauth
                    </ul>

                    <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-2 mt-3 mt-lg-0">
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link nav-pill" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="btn btn-light btn-sm fw-semibold px-3" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle user-menu" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    <span class="user-avatar">{{ strtoupper(substr(Auth::user()->name, 0, 1)) }}</span>
                                    <span class="d-lg-inline">{{ Auth::user()->name }}</span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-end shadow border-0 rounded-4 mt-2" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item py-2" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="flex-grow-1 py-4 py-lg-5">
            @yield('content')
        </main>
    </div>

    @livewireScripts
    @stack('scripts')
</body>
</html>
