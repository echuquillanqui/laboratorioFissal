@extends('layouts.app')

@section('content')
<div class="container-fluid px-3 px-lg-5 dashboard-page">
    @if (session('status'))
        <div class="alert alert-success border-0 shadow-sm rounded-4" role="alert">
            {{ session('status') }}
        </div>
    @endif

    <section class="dashboard-hero mb-4">
        <div>
            <span class="eyebrow text-primary">Panel principal</span>
            <h1 class="fw-bold mb-2">Hola, {{ Auth::user()->name }}.</h1>
            <p class="text-muted mb-0">Monitorea la operación diaria del laboratorio con indicadores claros y acciones rápidas.</p>
        </div>
        <div class="hero-actions">
            <a href="#" class="btn btn-outline-primary">Ver reportes</a>
            <a href="#" class="btn btn-primary">Nuevo registro</a>
        </div>
    </section>

    <div class="row g-4 mb-4">
        <div class="col-md-6 col-xl-3">
            <div class="metric-card metric-blue">
                <span class="metric-label">Pacientes atendidos</span>
                <strong>128</strong>
                <small>+12% esta semana</small>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="metric-card metric-green">
                <span class="metric-label">Resultados listos</span>
                <strong>86</strong>
                <small>24 pendientes de entrega</small>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="metric-card metric-gold">
                <span class="metric-label">Muestras en proceso</span>
                <strong>42</strong>
                <small>Prioridad alta: 7</small>
            </div>
        </div>
        <div class="col-md-6 col-xl-3">
            <div class="metric-card metric-purple">
                <span class="metric-label">Alertas clínicas</span>
                <strong>05</strong>
                <small>Requieren revisión</small>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-xl-8">
            <div class="card panel-card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex flex-wrap justify-content-between gap-3 mb-4">
                        <div>
                            <h2 class="h5 fw-bold mb-1">Actividad reciente</h2>
                            <p class="text-muted mb-0">Resumen de los últimos movimientos registrados.</p>
                        </div>
                        <span class="status-chip">En línea</span>
                    </div>

                    <div class="activity-list">
                        <div class="activity-item">
                            <span class="activity-dot bg-primary"></span>
                            <div>
                                <strong>Registro de muestra completado</strong>
                                <p>Hemograma completo asignado al área de hematología.</p>
                            </div>
                            <small>Hace 8 min</small>
                        </div>
                        <div class="activity-item">
                            <span class="activity-dot bg-success"></span>
                            <div>
                                <strong>Resultado validado</strong>
                                <p>Perfil lipídico disponible para impresión y envío.</p>
                            </div>
                            <small>Hace 22 min</small>
                        </div>
                        <div class="activity-item">
                            <span class="activity-dot bg-warning"></span>
                            <div>
                                <strong>Control de calidad pendiente</strong>
                                <p>Equipo de bioquímica requiere verificación preventiva.</p>
                            </div>
                            <small>Hace 1 h</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="card panel-card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <h2 class="h5 fw-bold mb-3">Accesos rápidos</h2>
                    <div class="quick-actions">
                        <a href="{{ route('patients.create') }}" class="quick-action">
                            <span>01</span>
                            Registrar paciente
                        </a>
                        <a href="{{ route('laboratory.results.index') }}" class="quick-action">
                            <span>02</span>
                            Cargar resultado
                        </a>
                        <a href="{{ route('laboratory.orders.index') }}" class="quick-action">
                            <span>03</span>
                            Gestionar órdenes
                        </a>
                        <a href="{{ route('laboratory.mass-orders.index') }}" class="quick-action">
                            <span>04</span>
                            Generar órdenes masivas
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
