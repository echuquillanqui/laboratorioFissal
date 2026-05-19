@extends('layouts.app')

@section('content')
<div class="container-fluid px-3 px-lg-5 clinical-page">
    <section class="clinical-hero clinical-hero-dialysis mb-4">
        <div class="clinical-hero-content">
            <a href="{{ route('patients.index') }}" class="clinical-back"><i class="fa-solid fa-arrow-left me-2"></i>Volver a pacientes</a>
            <span class="eyebrow text-primary">Nueva diálisis</span>
            <h1 class="fw-bold mb-2">Registro de sesión en pestañas</h1>
            <p class="text-muted mb-0">La captura se divide en contexto, prescripción, laboratorios y desenlace para disminuir carga visual.</p>
        </div>
        <div class="clinical-hero-badge dialysis">
            <i class="fa-solid fa-droplet"></i>
            <span>Diálisis</span>
        </div>
    </section>

    <div class="row g-4">
        <div class="col-xl-3">
            <aside class="patient-summary-card sticky-xl-top">
                <div class="patient-summary-header">
                    <div class="patient-avatar patient-avatar-lg"><i class="fa-solid fa-user-injured"></i></div>
                    <div>
                        <span class="text-muted small fw-bold text-uppercase">Paciente</span>
                        <h2 class="h6 fw-bold mb-0">{{ $patient['nombres_apellidos'] }}</h2>
                    </div>
                </div>
                <div class="patient-summary-grid single">
                    <div><small>DNI</small><strong>{{ $patient['dni'] }}</strong></div>
                    <div><small>Historia</small><strong>{{ $patient['numero_historia'] }}</strong></div>
                    <div><small>Sesión sugerida</small><strong>N° {{ (int) $patient['numero_sesion'] + 1 }}</strong></div>
                    <div><small>Ingreso</small><strong>{{ $patient['fecha_ingreso'] }}</strong></div>
                </div>
                <div class="dialysis-progress mt-4">
                    <span style="width: 68%"></span>
                </div>
                <small class="text-muted d-block mt-2">Maquetado preparado para validación por pestañas.</small>
            </aside>
        </div>

        <div class="col-xl-9">
            <form class="clinical-form-card dialysis-form-card">
                <ul class="nav clinical-tabs" id="dialysisTabs" role="tablist">
                    <li class="nav-item" role="presentation"><button class="nav-link active" id="context-tab" data-bs-toggle="tab" data-bs-target="#context" type="button" role="tab"><i class="fa-solid fa-user-doctor"></i>Contexto</button></li>
                    <li class="nav-item" role="presentation"><button class="nav-link" id="session-tab" data-bs-toggle="tab" data-bs-target="#session" type="button" role="tab"><i class="fa-solid fa-filter"></i>Sesión HD</button></li>
                    <li class="nav-item" role="presentation"><button class="nav-link" id="labs-tab" data-bs-toggle="tab" data-bs-target="#labs" type="button" role="tab"><i class="fa-solid fa-flask-vial"></i>Laboratorios</button></li>
                    <li class="nav-item" role="presentation"><button class="nav-link" id="outcome-tab" data-bs-toggle="tab" data-bs-target="#outcome" type="button" role="tab"><i class="fa-solid fa-chart-line"></i>Desenlace</button></li>
                </ul>

                <div class="tab-content clinical-tab-content" id="dialysisTabsContent">
                    <div class="tab-pane fade show active" id="context" role="tabpanel" aria-labelledby="context-tab" tabindex="0">
                        <div class="section-heading"><span><i class="fa-solid fa-notes-medical"></i></span><div><h2>Contexto clínico</h2><p>Comorbilidades, criticidad y clasificación renal.</p></div></div>
                        <div class="row g-3">
                            <div class="col-md-3"><label class="form-label">Peso</label><div class="input-group"><input class="form-control" type="number" step="0.01"><span class="input-group-text">kg</span></div></div>
                            <div class="col-md-3"><label class="form-label">Diagnóstico renal</label><select class="form-select"><option>LRA</option><option>ERC5</option><option>ERC5D</option></select></div>
                            <div class="col-md-3"><label class="form-label">Estadio ERC</label><select class="form-select"><option>G1</option><option>G2</option><option>G3</option><option>G4</option><option>G5</option></select></div>
                            <div class="col-md-3"><label class="form-label">Etiología</label><select class="form-select"><option>Sepsis</option><option>DM</option><option>HTA</option></select></div>
                            <div class="col-md-12"><label class="form-label">Comorbilidades</label><input class="form-control" placeholder="Diabetes, hipertensión, cardiopatía, otros"></div>
                            <div class="col-sm-6 col-lg-3"><div class="clinical-check"><input class="form-check-input" type="checkbox" id="ercPrevia"><label for="ercPrevia">ERC previa</label></div></div>
                            <div class="col-sm-6 col-lg-3"><div class="clinical-check"><input class="form-check-input" type="checkbox" id="hdCronica"><label for="hdCronica">HD crónica previa</label></div></div>
                            <div class="col-sm-6 col-lg-3"><div class="clinical-check"><input class="form-check-input" type="checkbox" id="uci"><label for="uci">UCI</label></div></div>
                            <div class="col-sm-6 col-lg-3"><label class="form-label">SOFA</label><input class="form-control" type="number" min="0" max="24" placeholder="0"></div>
                            <div class="col-sm-6"><div class="clinical-check"><input class="form-check-input" type="checkbox" id="vasopresoresDialisis"><label for="vasopresoresDialisis">Vasopresores</label></div></div>
                            <div class="col-sm-6"><div class="clinical-check"><input class="form-check-input" type="checkbox" id="vmDialisis"><label for="vmDialisis">Ventilación mecánica</label></div></div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="session" role="tabpanel" aria-labelledby="session-tab" tabindex="0">
                        <div class="section-heading"><span><i class="fa-solid fa-filter"></i></span><div><h2>Prescripción y eventos de sesión</h2><p>Datos técnicos de HD y complicaciones intradialíticas.</p></div></div>
                        <div class="row g-3">
                            <div class="col-md-4"><label class="form-label">Acceso vascular</label><select class="form-select"><option>CVC temporal</option><option>FAV</option></select></div>
                            <div class="col-md-4"><label class="form-label">Tipo de catéter</label><input class="form-control" placeholder="Yugular, femoral, permanente..."></div>
                            <div class="col-md-4"><label class="form-label">Indicación HD</label><select class="form-select"><option>Hiperkalemia</option><option>Sobrecarga</option></select></div>
                            <div class="col-md-3"><label class="form-label">N° sesión</label><input class="form-control" type="number" value="{{ (int) $patient['numero_sesion'] + 1 }}"></div>
                            <div class="col-md-3"><label class="form-label">Horas HD</label><input class="form-control" type="number" step="0.25" placeholder="3.5"></div>
                            <div class="col-md-3"><label class="form-label">Ultrafiltración</label><div class="input-group"><input class="form-control" type="number"><span class="input-group-text">ml</span></div></div>
                            <div class="col-md-3"><label class="form-label">Anticoagulación</label><input class="form-control" placeholder="Heparina / citrato"></div>
                            <div class="col-md-4"><div class="clinical-check danger"><input class="form-check-input" type="checkbox" id="hipotension"><label for="hipotension">Hipotensión intradiálisis</label></div></div>
                            <div class="col-md-4"><div class="clinical-check danger"><input class="form-check-input" type="checkbox" id="arritmias"><label for="arritmias">Arritmias</label></div></div>
                            <div class="col-md-4"><label class="form-label">Complicaciones</label><input class="form-control" placeholder="Calambres, sangrado, otros"></div>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="labs" role="tabpanel" aria-labelledby="labs-tab" tabindex="0">
                        <div class="section-heading"><span><i class="fa-solid fa-microscope"></i></span><div><h2>Panel de laboratorio</h2><p>Bioquímica, gases, hemograma e inflamación.</p></div></div>
                        <div class="row g-3">
                            @foreach ([['Urea inicial','mg/dL'], ['Creatinina inicial','mg/dL'], ['Potasio','mEq/L'], ['Sodio','mEq/L'], ['Bicarbonato','mEq/L'], ['Calcio','mg/dL'], ['Fósforo','mg/dL'], ['pH',''], ['Lactato','mmol/L'], ['Hemoglobina','g/dL'], ['Leucocitos','/mm³'], ['Plaquetas','/mm³'], ['Albúmina','g/dL'], ['PCR','mg/L']] as [$label, $unit])
                                <div class="col-sm-6 col-lg-3">
                                    <label class="form-label">{{ $label }}</label>
                                    <div class="input-group"><input class="form-control" type="number" step="0.01" placeholder="0.00">@if ($unit)<span class="input-group-text">{{ $unit }}</span>@endif</div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="tab-pane fade" id="outcome" role="tabpanel" aria-labelledby="outcome-tab" tabindex="0">
                        <div class="section-heading"><span><i class="fa-solid fa-flag-checkered"></i></span><div><h2>Desenlace y observaciones</h2><p>Evolución renal, destino final y comentarios clínicos.</p></div></div>
                        <div class="row g-3">
                            <div class="col-md-4"><label class="form-label">Diuresis 24h</label><div class="input-group"><input class="form-control" type="number"><span class="input-group-text">ml</span></div></div>
                            <div class="col-md-4"><label class="form-label">Días hospitalización</label><input class="form-control" type="number" placeholder="0"></div>
                            <div class="col-md-4"><label class="form-label">Destino final</label><select class="form-select"><option>ALTA</option><option>UCI</option><option>FALLECIDO</option></select></div>
                            <div class="col-md-4"><div class="clinical-check success"><input class="form-check-input" type="checkbox" id="recuperacion"><label for="recuperacion">Recuperación renal</label></div></div>
                            <div class="col-md-4"><div class="clinical-check"><input class="form-check-input" type="checkbox" id="hdAlta"><label for="hdAlta">HD al alta</label></div></div>
                            <div class="col-md-4"><div class="clinical-check danger"><input class="form-check-input" type="checkbox" id="mortalidad"><label for="mortalidad">Mortalidad 28 días</label></div></div>
                            <div class="col-12"><label class="form-label">Observaciones</label><textarea class="form-control" rows="4" placeholder="Registra detalles de tolerancia, plan y recomendaciones de seguimiento"></textarea></div>
                        </div>
                    </div>
                </div>

                <div class="clinical-form-actions">
                    <button type="button" class="btn btn-outline-secondary"><i class="fa-regular fa-circle-xmark me-2"></i>Cancelar</button>
                    <button type="button" class="btn btn-outline-primary"><i class="fa-regular fa-file-lines me-2"></i>Guardar borrador</button>
                    <button type="button" class="btn btn-primary"><i class="fa-solid fa-floppy-disk me-2"></i>Guardar diálisis</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
