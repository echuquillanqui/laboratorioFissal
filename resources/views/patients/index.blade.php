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
            <button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#patientCreateModal">
                <i class="fa-solid fa-user-plus me-2"></i>Agregar paciente
            </button>
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

            <div class="selection-toolbar mb-4">
                <div class="selection-actions">
                    <button type="button" class="btn btn-sm btn-outline-primary" id="selectAllPatients">Marcar todos</button>
                    <button type="button" class="btn btn-sm btn-outline-secondary" id="clearAllPatients">Desmarcar todos</button>
                    <span class="selection-counter" id="selectedPatientsCounter">0 seleccionados</span>
                </div>
                <div class="autocomplete-box">
                    <label class="form-label mb-1">Búsqueda inteligente</label>
                    <input type="text" class="form-control" id="patientAutocomplete" placeholder="Escribe nombre, DNI, historia...">
                    <div class="autocomplete-results" id="patientAutocompleteResults"></div>
                </div>
            </div>

            <div class="row g-3">
                @forelse ($patients as $patient)
                    <div class="col-xl-6">
                        <article class="patient-option-card" id="patient-card-{{ $patient['id'] }}">
                            <div class="patient-option-main">
                                <input class="form-check-input patient-select-checkbox" type="checkbox" value="{{ $patient['id'] }}">
                                <div class="patient-avatar"><i class="fa-solid fa-user-injured"></i></div>
                                <div>
                                    <h3 class="h6 fw-bold mb-1">{{ $patient['nombres_apellidos'] }}</h3>
                                    <div class="d-flex flex-wrap gap-2">
                                        <span class="info-chip"><i class="fa-regular fa-id-card me-1"></i>DNI {{ $patient['dni'] }}</span>
                                        <span class="info-chip"><i class="fa-solid fa-file-waveform me-1"></i>{{ $patient['numero_historia'] }}</span>
                                        <span class="credential-pill"><i class="fa-solid fa-hashtag me-1"></i>{{ $patient['codigo_unico'] }}</span>
                                        <span class="info-chip"><i class="fa-solid fa-shield-heart me-1"></i>{{ $patient['regimen'] ?? 'Sin régimen' }}</span>
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


    <div class="modal fade" id="patientCreateModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <form method="POST" action="{{ route('patients.store') }}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Registrar paciente</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label">Nombres y apellidos</label>
                                <input class="form-control" name="nombres_apellidos" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">DNI</label>
                                <input class="form-control" name="dni" maxlength="8" required>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Historia clínica</label>
                                <input class="form-control" value="Se genera automáticamente" readonly>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Código único</label>
                                <input class="form-control" name="codigo_unico" maxlength="7" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Fecha ingreso</label>
                                <input class="form-control" type="date" name="fecha_ingreso" value="{{ now()->toDateString() }}" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Edad</label>
                                <input class="form-control" type="number" name="edad" min="0" max="120" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Sexo</label>
                                <select class="form-select" name="sexo" required><option value="F">F</option><option value="M">M</option></select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Número sesión</label>
                                <input class="form-control" type="number" name="numero_sesion" min="0" value="0" required>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Régimen</label>
                                <select class="form-select" name="regimen">
                                    <option value="">Sin especificar</option><option value="SIS">SIS</option><option value="ESSALUD">ESSALUD</option><option value="SALUDPOL">SALUDPOL</option><option value="PARTICULAR">PARTICULAR</option><option value="OTROS">OTROS</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button class="btn btn-primary" type="submit">Registrar paciente</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const checks = () => Array.from(document.querySelectorAll('.patient-select-checkbox'));
    const counter = document.getElementById('selectedPatientsCounter');

    const updateCounter = () => {
        counter.textContent = `${checks().filter((item) => item.checked).length} seleccionados`;
    };

    document.getElementById('selectAllPatients')?.addEventListener('click', () => {
        checks().forEach((item) => item.checked = true);
        updateCounter();
    });

    document.getElementById('clearAllPatients')?.addEventListener('click', () => {
        checks().forEach((item) => item.checked = false);
        updateCounter();
    });

    checks().forEach((item) => item.addEventListener('change', updateCounter));
    updateCounter();

    const input = document.getElementById('patientAutocomplete');
    const results = document.getElementById('patientAutocompleteResults');
    let timer = null;

    input?.addEventListener('input', () => {
        clearTimeout(timer);
        const query = input.value.trim();

        if (query.length < 2) {
            results.innerHTML = '';
            return;
        }

        timer = setTimeout(async () => {
            const response = await fetch(`{{ route('patients.autocomplete') }}?q=${encodeURIComponent(query)}`);
            const data = await response.json();

            if (!Array.isArray(data) || data.length === 0) {
                results.innerHTML = '<div class="autocomplete-item">Sin resultados</div>';
                return;
            }

            results.innerHTML = data.map((patient) => `
                <button type="button" class="autocomplete-item" data-patient-id="${patient.id}">
                    <strong>${patient.nombres_apellidos}</strong><br>
                    <small>DNI ${patient.dni} · ${patient.numero_historia} · ${patient.codigo_unico}</small>
                </button>
            `).join('');

            results.querySelectorAll('.autocomplete-item[data-patient-id]').forEach((item) => {
                item.addEventListener('click', () => {
                    const id = item.getAttribute('data-patient-id');
                    const card = document.getElementById(`patient-card-${id}`);
                    card?.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    card?.classList.add('ring-highlight');
                    setTimeout(() => card?.classList.remove('ring-highlight'), 1600);
                });
            });
        }, 250);
    });
});
</script>
@endpush
