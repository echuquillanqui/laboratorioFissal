@extends('layouts.app')

@section('content')
<div class="container-fluid px-3 px-lg-5 clinical-page">
    <section class="clinical-hero mb-4">
        <div class="clinical-hero-content">
            <span class="eyebrow text-primary">Pacientes nefrológicos</span>
            <h1 class="fw-bold mb-2">Bandeja de pacientes</h1>
            <p class="text-muted mb-0">Gestiona pacientes y abre los flujos clínicos desde la ficha de cada paciente.</p>
        </div>
        <div class="clinical-hero-actions">
            <a href="{{ route('patients.create') }}" class="btn btn-primary">
                <i class="fa-solid fa-user-plus me-2"></i>Agregar paciente
            </a>
        </div>
    </section>

    @if (session('status'))
        <div class="alert alert-success border-0 rounded-4 shadow-sm" role="alert">
            <i class="fa-solid fa-circle-check me-2"></i>{{ session('status') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-warning border-0 rounded-4 shadow-sm" role="alert">
            <i class="fa-solid fa-triangle-exclamation me-2"></i>{{ session('error') }}
        </div>
    @endif

    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="clinical-stat clinical-stat-blue">
                <i class="fa-solid fa-hospital-user"></i>
                <span>Pacientes registrados</span>
                <strong>{{ $patients->total() }}</strong>
                <small>Total disponible en la bandeja</small>
            </div>
        </div>
        <div class="col-md-4">
            <div class="clinical-stat clinical-stat-green">
                <i class="fa-solid fa-notes-medical"></i>
                <span>Flujo de consulta</span>
                <strong>HD</strong>
                <small>Ficha inicial desde cada paciente</small>
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
                    <p class="text-muted mb-0">Cada card permite generar atenciones, editar datos o eliminar si no tiene atenciones asociadas.</p>
                </div>
                <form class="input-group clinical-search" method="GET" action="{{ route('patients.index') }}">
                    <span class="input-group-text"><i class="fa-solid fa-magnifying-glass"></i></span>
                    <input class="form-control" name="buscar" type="text" value="{{ request('buscar') }}" placeholder="Buscar por DNI, historia o nombre">
                    <button class="btn btn-primary" type="submit">Buscar</button>
                </form>
            </div>

            <div class="row g-3">
                @forelse ($patients as $patient)
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
                            <div class="patient-record-summary">
                                <span><i class="fa-solid fa-stethoscope me-1"></i>{{ $patient['consultas_count'] ?? 0 }} consultas</span>
                                <span><i class="fa-solid fa-droplet me-1"></i>{{ $patient['dialisis_count'] ?? 0 }} diálisis</span>
                            </div>
                            <div class="patient-option-actions">
                                <a href="{{ route('patients.consults.create', $patient['id']) }}" class="btn btn-outline-primary">
                                    <i class="fa-solid fa-stethoscope me-2"></i>Generar consulta
                                </a>
                                <a href="{{ route('patients.dialysis.create', $patient['id']) }}" class="btn btn-primary">
                                    <i class="fa-solid fa-droplet me-2"></i>Generar diálisis
                                </a>
                                <a href="{{ route('patients.edit', $patient['id']) }}" class="btn btn-outline-secondary">
                                    <i class="fa-solid fa-pen-to-square me-2"></i>Editar
                                </a>
                                @if ($patient['can_delete'] ?? true)
                                    <form method="POST" action="{{ route('patients.destroy', $patient['id']) }}" onsubmit="return confirm('¿Eliminar este paciente?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-outline-danger" type="submit">
                                            <i class="fa-solid fa-trash-can me-2"></i>Eliminar
                                        </button>
                                    </form>
                                @else
                                    <button class="btn btn-outline-danger" type="button" disabled title="No se puede eliminar porque tiene consultas o diálisis registradas">
                                        <i class="fa-solid fa-lock me-2"></i>No eliminable
                                    </button>
                                @endif
                            </div>
                        </article>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="empty-state-card text-center">
                            <i class="fa-solid fa-user-plus"></i>
                            <h3 class="h5 fw-bold mt-3">No hay pacientes para mostrar</h3>
                            <p class="text-muted">Registra un paciente nuevo o limpia el filtro de búsqueda.</p>
                            <a href="{{ route('patients.create') }}" class="btn btn-primary">Agregar paciente</a>
                        </div>
                    </div>
                @endforelse
            </div>

            <div class="clinical-pagination mt-4">
                {{ $patients->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
