<div class="clinical-form-card p-4 p-lg-5">
    <form wire:submit="save">
        <div class="row g-3">
            <div class="col-lg-6">
                @include('livewire.hemodialysis.partials.patient-selector', ['id' => 'admission-patient'])
                @include('livewire.hemodialysis.partials.patient-summary')
            </div>
            <div class="col-md-3">
                <label class="form-label">Fecha ingreso HD</label>
                <input type="date" class="form-control @error('fecha_ingreso_hd') is-invalid @enderror" wire:model="fecha_ingreso_hd">
                @error('fecha_ingreso_hd') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-3">
                <label class="form-label">Estado</label>
                <select class="form-select @error('estado') is-invalid @enderror" wire:model="estado">
                    <option value="borrador">Borrador</option>
                    <option value="cerrado">Cerrado</option>
                </select>
                @error('estado') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <div class="col-md-4">
                <label class="form-label">Procedencia</label>
                <input class="form-control" wire:model="procedencia">
            </div>
            <div class="col-md-4">
                <label class="form-label">Diagnóstico renal</label>
                <input class="form-control" wire:model="diagnostico_renal">
            </div>
            <div class="col-md-4">
                <label class="form-label">Etiología</label>
                <input class="form-control" wire:model="etiologia">
            </div>
        </div>
        <ul class="nav clinical-tabs mt-4" role="tablist">
            <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#adm-clinica" type="button">Clínica</button></li>
            <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#adm-plan" type="button">Plan</button></li>
        </ul>
        <div class="tab-content clinical-tab-content">
            <div id="adm-clinica" class="tab-pane fade show active">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Antecedentes</label>
                        <textarea class="form-control" rows="5" wire:model="antecedentes"></textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Comorbilidades</label>
                        <textarea class="form-control" rows="5" wire:model="comorbilidades"></textarea>
                    </div>
                </div>
            </div>
            <div id="adm-plan" class="tab-pane fade">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label">Acceso vascular inicial</label>
                        <input class="form-control" wire:model="acceso_vascular_inicial">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Indicación HD</label>
                        <input class="form-control" wire:model="indicacion_hd">
                    </div>
                    <div class="col-12">
                        <label class="form-label">Observaciones</label>
                        <textarea class="form-control" rows="4" wire:model="observaciones"></textarea>
                    </div>
                </div>
            </div>
        </div>
        <div class="clinical-form-actions">
            <a class="btn btn-outline-secondary" href="{{ route('hemodialysis.admissions.index') }}" wire:navigate>Cancelar</a>
            <button class="btn btn-primary">{{ $submitLabel }}</button>
        </div>
    </form>
</div>
