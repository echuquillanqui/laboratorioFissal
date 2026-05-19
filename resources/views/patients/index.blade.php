@extends('layouts.app')

@section('content')
<div class="container-fluid px-3 px-lg-5 clinical-page">
    <section class="clinical-hero mb-4">
        <div class="clinical-hero-content">
            <span class="eyebrow text-primary">Pacientes nefrológicos</span>
            <h1 class="fw-bold mb-2">Selecciona el flujo clínico a generar</h1>
            <p class="text-muted mb-0">Desde esta bandeja se abrirán pantallas completas para consulta o diálisis; no se utilizan modales para preservar espacio clínico.</p>
        </div>
        <div class="clinical-hero-actions">
            <a href="{{ route('consults.create') }}" class="btn btn-light">
                <i class="fa-solid fa-stethoscope me-2"></i>Consulta rápida
            </a>
            <a href="{{ route('dialysis.create') }}" class="btn btn-primary">
                <i class="fa-solid fa-droplet me-2"></i>Diálisis rápida
            </a>
        </div>
    </section>

    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="clinical-stat clinical-stat-blue">
                <i class="fa-solid fa-hospital-user"></i>
                <span>Pacientes en seguimiento</span>
                <strong>{{ count($patients) }}</strong>
                <small>Lista operativa para generar atenciones</small>
            </div>
        </div>
        <div class="col-md-4">
            <div class="clinical-stat clinical-stat-green">
                <i class="fa-solid fa-notes-medical"></i>
                <span>Consultas disponibles</span>
                <strong>HD</strong>
                <small>Ficha inicial nefrológica</small>
            </div>
        </div>
        <div class="col-md-4">
            <div class="clinical-stat clinical-stat-purple">
                <i class="fa-solid fa-filter"></i>
                <span>Diálisis por sesiones</span>
                <strong>Tabs</strong>
                <small>Registro segmentado y ordenado</small>
            </div>
        </div>
    </div>

    <div class="card clinical-card border-0 shadow-sm">
        <div class="card-body p-4">
            <div class="d-flex flex-wrap justify-content-between gap-3 align-items-center mb-4">
                <div>
                    <h2 class="h5 fw-bold mb-1">Bandeja de pacientes</h2>
                    <p class="text-muted mb-0">Cada paciente expone las opciones “Generar consulta” y “Generar diálisis”.</p>
                </div>
                <div class="input-group clinical-search">
                    <span class="input-group-text"><i class="fa-solid fa-magnifying-glass"></i></span>
                    <input class="form-control" type="text" placeholder="Buscar por DNI, historia o nombre">
                </div>
            </div>

            <div class="row g-3">
                @foreach ($patients as $patient)
                    <div class="col-xl-6">
                        <article class="patient-option-card">
                            <div class="patient-option-main">
                                <div class="patient-avatar"><i class="fa-solid fa-user-injured"></i></div>
                                <div>
                                    <h3 class="h6 fw-bold mb-1">{{ $patient['nombres_apellidos'] }}</h3>
                                    <div class="d-flex flex-wrap gap-2">
                                        <span class="info-chip"><i class="fa-regular fa-id-card me-1"></i>DNI {{ $patient['dni'] }}</span>
                                        <span class="info-chip"><i class="fa-solid fa-file-waveform me-1"></i>{{ $patient['numero_historia'] }}</span>
                                        <span class="credential-pill"><i class="fa-solid fa-hashtag me-1"></i>{{ $patient['codigo_unico'] }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="patient-option-meta">
                                <span><i class="fa-solid fa-cake-candles"></i>{{ $patient['edad'] }} años</span>
                                <span><i class="fa-solid fa-venus-mars"></i>{{ $patient['sexo'] }}</span>
                                <span><i class="fa-solid fa-calendar-check"></i>{{ $patient['fecha_ingreso'] }}</span>
                                <span><i class="fa-solid fa-rotate"></i>Sesión {{ $patient['numero_sesion'] }}</span>
                            </div>
                            <div class="patient-option-actions">
                                <a href="{{ route('patients.consults.create', $patient['id']) }}" class="btn btn-outline-primary">
                                    <i class="fa-solid fa-stethoscope me-2"></i>Generar consulta
                                </a>
                                <a href="{{ route('patients.dialysis.create', $patient['id']) }}" class="btn btn-primary">
                                    <i class="fa-solid fa-droplet me-2"></i>Generar diálisis
                                </a>
                            </div>
                        </article>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
