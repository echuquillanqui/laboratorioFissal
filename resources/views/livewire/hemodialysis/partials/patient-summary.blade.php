@if($patientSummary)
    <div class="alert alert-info border-0 rounded-4 shadow-sm mt-3">
        <div class="row g-2 small">
            <div class="col-md-4"><strong>Historia clínica:</strong> {{ $patientSummary['numero_historia'] ?? '—' }}</div>
            <div class="col-md-4"><strong>Paciente:</strong> {{ $patientSummary['nombres_apellidos'] ?? '—' }}</div>
            <div class="col-md-4"><strong>DNI:</strong> {{ $patientSummary['dni'] ?? '—' }}</div>
            <div class="col-md-3"><strong>Sexo:</strong> {{ $patientSummary['sexo'] ?? '—' }}</div>
            <div class="col-md-3"><strong>Edad:</strong> {{ $patientSummary['edad'] ?? '—' }}</div>
            <div class="col-md-3"><strong>F. nacimiento:</strong> {{ $patientSummary['fecha_nacimiento'] ?? '—' }}</div>
            <div class="col-md-3"><strong>Teléfono:</strong> {{ $patientSummary['telefono'] ?? '—' }}</div>
            <div class="col-12"><strong>Dirección:</strong> {{ $patientSummary['direccion'] ?? '—' }}</div>
        </div>
    </div>
@endif
