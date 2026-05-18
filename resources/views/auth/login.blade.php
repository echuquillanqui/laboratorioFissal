@extends('layouts.app')

@section('content')
<div class="container auth-container">
    <div class="row align-items-center justify-content-center g-4 g-xl-5">
        <div class="col-lg-6">
            <div class="auth-hero text-white">
                <span class="eyebrow">Acceso seguro al laboratorio</span>
                <h1 class="display-5 fw-bold mt-3 mb-3">Control clínico con una experiencia clara, moderna y confiable.</h1>
                <p class="lead mb-4">Ingresa para gestionar pacientes, resultados y reportes desde un entorno diseñado para equipos de salud.</p>

                <div class="row g-3 auth-highlights">
                    <div class="col-sm-6">
                        <div class="highlight-card">
                            <span class="highlight-icon">✓</span>
                            <div>
                                <strong>Datos protegidos</strong>
                                <small>Autenticación y sesiones seguras.</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="highlight-card">
                            <span class="highlight-icon">↗</span>
                            <div>
                                <strong>Flujo ágil</strong>
                                <small>Acceso rápido al panel operativo.</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="card auth-card border-0 shadow-lg">
                <div class="card-body p-4 p-md-5">
                    <div class="text-center mb-4">
                        <span class="auth-logo">LF</span>
                        <h2 class="h3 fw-bold mt-3 mb-1">Bienvenido</h2>
                        <p class="text-muted mb-0">Inicia sesión con tu DNI, usuario o correo.</p>
                    </div>

                    <form method="POST" action="{{ route('login') }}" class="auth-form">
                        @csrf

                        <div class="mb-3">
                            <label for="login" class="form-label fw-semibold">{{ __('DNI, Username, or Email') }}</label>
                            <input id="login" type="text" class="form-control form-control-lg @error('login') is-invalid @enderror" name="login" value="{{ old('login') }}" required autocomplete="username" autofocus placeholder="Ej. 12345678 o usuario@correo.com">

                            @error('login')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <label for="password" class="form-label fw-semibold">{{ __('Password') }}</label>
                                @if (Route::has('password.request'))
                                    <a class="small auth-link" href="{{ route('password.request') }}">
                                        {{ __('Forgot Your Password?') }}
                                    </a>
                                @endif
                            </div>
                            <input id="password" type="password" class="form-control form-control-lg @error('password') is-invalid @enderror" name="password" required autocomplete="current-password" placeholder="Ingresa tu contraseña">

                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label" for="remember">
                                    {{ __('Remember Me') }}
                                </label>
                            </div>
                            <span class="security-badge">SSL Ready</span>
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg w-100 auth-submit">
                            {{ __('Login') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
