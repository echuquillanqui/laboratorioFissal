@extends('layouts.app')

@section('content')
<div class="container-fluid px-3 px-lg-5 clinical-page">
    <a href="{{ route('patients.index') }}" class="clinical-back"><i class="fa-solid fa-arrow-left me-2"></i>Volver a pacientes</a>

    <section class="clinical-hero mb-4">
        <div class="clinical-hero-content">
            <span class="eyebrow text-primary">{{ $isEditing ? 'Editar paciente' : 'Nuevo paciente' }}</span>
            <h1 class="fw-bold mb-2">{{ $isEditing ? 'Actualizar datos del paciente' : 'Agregar nuevo paciente' }}</h1>
            <p class="text-muted mb-0">Completa la identificación y datos operativos para habilitar los flujos clínicos.</p>
        </div>
        <div class="clinical-hero-badge">
            <div><i class="fa-solid fa-user-injured"></i><span>Paciente</span></div>
        </div>
    </section>

    <div class="clinical-form-card p-4 p-lg-5">
        <form method="POST" action="{{ $isEditing ? route('patients.update', $patient['id']) : route('patients.store') }}">
            @csrf
            @if ($isEditing)
                @method('PUT')
            @endif

            <div class="form-section">
                <div class="section-title"><span>1</span><div><h2>Identificación</h2><p>Datos únicos del paciente dentro del sistema.</p></div></div>
                <div class="row g-3">
                    <div class="col-lg-6">
                        <label class="form-label">Nombres y apellidos</label>
                        <input class="form-control @error('nombres_apellidos') is-invalid @enderror" name="nombres_apellidos" value="{{ old('nombres_apellidos', $patient['nombres_apellidos']) }}" required>
                        @error('nombres_apellidos')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <label class="form-label">DNI</label>
                        <input class="form-control @error('dni') is-invalid @enderror" name="dni" value="{{ old('dni', $patient['dni']) }}" maxlength="8" required>
                        @error('dni')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <label class="form-label">Historia clínica</label>
                        <input class="form-control @error('numero_historia') is-invalid @enderror" name="numero_historia" value="{{ old('numero_historia', $patient['numero_historia']) }}" required>
                        @error('numero_historia')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <label class="form-label">Código único</label>
                        <input class="form-control @error('codigo_unico') is-invalid @enderror" name="codigo_unico" value="{{ old('codigo_unico', $patient['codigo_unico']) }}" maxlength="7" required>
                        @error('codigo_unico')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            <div class="form-section">
                <div class="section-title"><span>2</span><div><h2>Datos clínicos base</h2><p>Información visible en la bandeja y prellenada en formularios.</p></div></div>
                <div class="row g-3">
                    <div class="col-md-6 col-lg-3">
                        <label class="form-label">Fecha de ingreso</label>
                        <input class="form-control @error('fecha_ingreso') is-invalid @enderror" type="date" name="fecha_ingreso" value="{{ old('fecha_ingreso', $patient['fecha_ingreso']) }}" required>
                        @error('fecha_ingreso')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <label class="form-label">Edad</label>
                        <input class="form-control @error('edad') is-invalid @enderror" type="number" name="edad" value="{{ old('edad', $patient['edad']) }}" min="0" max="120" required>
                        @error('edad')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <label class="form-label">Sexo</label>
                        <select class="form-select @error('sexo') is-invalid @enderror" name="sexo" required>
                            <option value="F" @selected(old('sexo', $patient['sexo']) === 'F')>F</option>
                            <option value="M" @selected(old('sexo', $patient['sexo']) === 'M')>M</option>
                        </select>
                        @error('sexo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-6 col-lg-3">
                        <label class="form-label">Número de sesión</label>
                        <input class="form-control @error('numero_sesion') is-invalid @enderror" type="number" name="numero_sesion" value="{{ old('numero_sesion', $patient['numero_sesion']) }}" min="0" required>
                        @error('numero_sesion')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            <div class="clinical-form-actions">
                <a href="{{ route('patients.index') }}" class="btn btn-outline-secondary">Cancelar</a>
                <button class="btn btn-primary" type="submit"><i class="fa-solid fa-floppy-disk me-2"></i>{{ $isEditing ? 'Guardar cambios' : 'Registrar paciente' }}</button>
            </div>
        </form>
    </div>
</div>
@endsection
