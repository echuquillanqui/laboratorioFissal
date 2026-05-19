@extends('layouts.app')

@section('content')
<div class="container-fluid px-3 px-lg-5 clinical-page">
    <section class="clinical-hero clinical-hero-consult mb-4">
        <div class="clinical-hero-content">
            <a href="{{ route('patients.index') }}" class="clinical-back"><i class="fa-solid fa-arrow-left me-2"></i>Volver a pacientes</a>
            <span class="eyebrow text-primary">Nueva consulta</span>
            <h1 class="fw-bold mb-2">Evaluación nefrológica integral</h1>
            <p class="text-muted mb-0">Maquetado completo para registrar procedencia, diagnóstico renal, laboratorios iniciales, soporte crítico y destino.</p>
        </div>
        <div class="clinical-hero-badge">
            <i class="fa-solid fa-stethoscope"></i>
            <span>Consulta</span>
        </div>
    </section>

    <div class="row g-4">
        <div class="col-xl-4">
            <aside class="patient-summary-card sticky-xl-top">
                <div class="patient-summary-header">
                    <div class="patient-avatar patient-avatar-lg"><i class="fa-solid fa-user-injured"></i></div>
                    <div>
                        <span class="text-muted small fw-bold text-uppercase">Paciente seleccionado</span>
                        <h2 class="h5 fw-bold mb-0">{{ $patient['nombres_apellidos'] }}</h2>
                    </div>
                </div>
                <div class="patient-summary-grid">
                    <div><small>DNI</small><strong>{{ $patient['dni'] }}</strong></div>
                    <div><small>Historia</small><strong>{{ $patient['numero_historia'] }}</strong></div>
                    <div><small>Código único</small><strong>{{ $patient['codigo_unico'] }}</strong></div>
                    <div><small>Edad / Sexo</small><strong>{{ $patient['edad'] }} años · {{ $patient['sexo'] }}</strong></div>
                </div>
                <div class="clinical-timeline mt-4">
                    <div class="clinical-timeline-item active"><i class="fa-solid fa-user-check"></i><span>Paciente validado</span></div>
                    <div class="clinical-timeline-item active"><i class="fa-solid fa-clipboard-list"></i><span>Consulta en edición</span></div>
                    <div class="clinical-timeline-item"><i class="fa-solid fa-floppy-disk"></i><span>Pendiente de guardado</span></div>
                </div>
            </aside>
        </div>

        <div class="col-xl-8">
            <form class="clinical-form-card">
                <input type="hidden" name="id_paciente" value="{{ $patient['id'] }}">

                <div class="form-section patient-prefill-section">
                    <div class="section-heading">
                        <span><i class="fa-solid fa-user-check"></i></span>
                        <div>
                            <h2>Paciente prellenado</h2>
                            <p>Estos datos llegan desde la opción “Generar consulta” seleccionada en pacientes.</p>
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nombres y apellidos</label>
                            <input class="form-control" name="paciente_nombre" value="{{ $patient['nombres_apellidos'] }}" readonly>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <label class="form-label">DNI</label>
                            <input class="form-control" name="paciente_dni" value="{{ $patient['dni'] }}" readonly>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <label class="form-label">Historia clínica</label>
                            <input class="form-control" name="numero_historia" value="{{ $patient['numero_historia'] }}" readonly>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <label class="form-label">Código único</label>
                            <input class="form-control" name="codigo_unico" value="{{ $patient['codigo_unico'] }}" readonly>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <label class="form-label">Edad</label>
                            <input class="form-control" name="edad" value="{{ $patient['edad'] }} años" readonly>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <label class="form-label">Sexo</label>
                            <input class="form-control" name="sexo" value="{{ $patient['sexo'] }}" readonly>
                        </div>
                        <div class="col-sm-6 col-md-3">
                            <label class="form-label">Fecha de ingreso</label>
                            <input class="form-control" name="fecha_ingreso" value="{{ $patient['fecha_ingreso'] }}" readonly>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <div class="section-heading">
                        <span><i class="fa-solid fa-location-dot"></i></span>
                        <div>
                            <h2>Datos de ingreso</h2>
                            <p>Procedencia y clasificación clínica inicial.</p>
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Procedencia</label>
                            <select class="form-select"><option>Emergencia</option><option>UCI</option><option>Medicina</option></select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Diagnóstico renal</label>
                            <select class="form-select"><option>LRA</option><option>ERC5</option><option>ERC5D</option></select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Etiología</label>
                            <select class="form-select"><option>Sepsis</option><option>DM</option><option>HTA</option></select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Acceso vascular</label>
                            <select class="form-select"><option>CVC temporal</option><option>FAV</option></select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Indicación de HD</label>
                            <select class="form-select"><option>Hiperkalemia</option><option>Sobrecarga</option></select>
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <div class="section-heading">
                        <span><i class="fa-solid fa-vial-circle-check"></i></span>
                        <div>
                            <h2>Laboratorios iniciales</h2>
                            <p>Valores basales antes de la terapia o intervención.</p>
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-sm-6 col-lg-4"><label class="form-label">Urea inicial</label><div class="input-group"><input class="form-control" type="number" step="0.01" placeholder="0.00"><span class="input-group-text">mg/dL</span></div></div>
                        <div class="col-sm-6 col-lg-4"><label class="form-label">Creatinina inicial</label><div class="input-group"><input class="form-control" type="number" step="0.01" placeholder="0.00"><span class="input-group-text">mg/dL</span></div></div>
                        <div class="col-sm-6 col-lg-4"><label class="form-label">Potasio inicial</label><div class="input-group"><input class="form-control" type="number" step="0.01" placeholder="0.00"><span class="input-group-text">mEq/L</span></div></div>
                        <div class="col-sm-6 col-lg-6"><label class="form-label">Hemoglobina</label><div class="input-group"><input class="form-control" type="number" step="0.01" placeholder="0.00"><span class="input-group-text">g/dL</span></div></div>
                        <div class="col-sm-6 col-lg-6"><label class="form-label">Albúmina</label><div class="input-group"><input class="form-control" type="number" step="0.01" placeholder="0.00"><span class="input-group-text">g/dL</span></div></div>
                    </div>
                </div>

                <div class="form-section">
                    <div class="section-heading">
                        <span><i class="fa-solid fa-heart-pulse"></i></span>
                        <div>
                            <h2>Soporte y evolución</h2>
                            <p>Condiciones de soporte crítico y destino de la consulta.</p>
                        </div>
                    </div>
                    <div class="row g-3 align-items-end">
                        <div class="col-md-4"><div class="clinical-check"><input class="form-check-input" type="checkbox" id="hdPrevia"><label for="hdPrevia">HD crónica previa</label></div></div>
                        <div class="col-md-4"><div class="clinical-check"><input class="form-check-input" type="checkbox" id="vasopresores"><label for="vasopresores">Vasopresores</label></div></div>
                        <div class="col-md-4"><div class="clinical-check"><input class="form-check-input" type="checkbox" id="ventilacion"><label for="ventilacion">Ventilación mecánica</label></div></div>
                        <div class="col-md-8"><label class="form-label">Complicación HD</label><input class="form-control" placeholder="Describe complicaciones o incidencias"></div>
                        <div class="col-md-4"><label class="form-label">Destino</label><select class="form-select"><option>ALTA</option><option>UCI</option><option>FALLECIDO</option></select></div>
                    </div>
                </div>

                <div class="clinical-form-actions">
                    <button type="button" class="btn btn-outline-secondary"><i class="fa-regular fa-circle-xmark me-2"></i>Cancelar</button>
                    <button type="button" class="btn btn-primary"><i class="fa-solid fa-floppy-disk me-2"></i>Guardar consulta</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
