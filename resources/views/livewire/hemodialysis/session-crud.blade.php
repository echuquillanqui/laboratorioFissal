@include('livewire.hemodialysis.partials.header', ['title' => 'Ficha de Hemodiálisis'])
<div class="clinical-form-card p-4 p-lg-5">
    <form wire:submit="save">
        <div class="row g-3">
            <div class="col-lg-6">@include('livewire.hemodialysis.partials.patient-selector', ['id' => 'session-patient']) @include('livewire.hemodialysis.partials.patient-summary')</div>
            <div class="col-md-2"><label class="form-label">N° sesión</label><input type="number" class="form-control" wire:model="numero_sesion"></div>
            <div class="col-md-2"><label class="form-label">Fecha</label><input type="date" class="form-control" wire:model="fecha_sesion"></div>
            <div class="col-md-2"><label class="form-label">Estado</label><select class="form-select" wire:model="estado"><option>borrador</option><option>cerrado</option></select></div>
            <div class="col-md-3"><label class="form-label">Ingreso relacionado</label><select class="form-select" wire:model="hemodialysis_admission_id"><option value="">Sin vincular</option>@foreach($admissions as $admission)<option value="{{ $admission->id }}">{{ $admission->fecha_ingreso_hd?->format('Y-m-d') }}</option>@endforeach</select></div>
        </div>
        <ul class="nav clinical-tabs mt-4"><li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#session-tech" type="button">Técnica</button></li><li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#session-events" type="button">Eventos</button></li></ul>
        <div class="tab-content clinical-tab-content">
            <div id="session-tech" class="tab-pane fade show active"><div class="row g-3">
                @foreach([['hora_inicio','Hora inicio','time'],['hora_fin','Hora fin','time'],['peso_pre','Peso pre','number'],['peso_post','Peso post','number'],['horas_hd','Horas HD','number'],['ultrafiltracion_ml','UF ml','number']] as [$field,$label,$type])<div class="col-md-2"><label class="form-label">{{ $label }}</label><input type="{{ $type }}" step="0.01" class="form-control" wire:model="{{ $field }}"></div>@endforeach
                @foreach([['acceso_vascular','Acceso vascular'],['tipo_cateter','Tipo catéter'],['anticoagulacion','Anticoagulación'],['flujo_sanguineo','Flujo sanguíneo'],['flujo_dializado','Flujo dializado'],['dializador','Dializador']] as [$field,$label])<div class="col-md-4"><label class="form-label">{{ $label }}</label><input class="form-control" wire:model="{{ $field }}"></div>@endforeach
            </div></div>
            <div id="session-events" class="tab-pane fade"><div class="row g-3"><div class="col-md-3"><div class="clinical-check danger"><input class="form-check-input" type="checkbox" wire:model="hipotension_intradialisis" id="hipo"><label for="hipo">Hipotensión intradiálisis</label></div></div><div class="col-md-3"><div class="clinical-check danger"><input class="form-check-input" type="checkbox" wire:model="arritmias" id="arrit"><label for="arrit">Arritmias</label></div></div>@foreach([['complicaciones','Complicaciones'],['prescripcion_medica','Prescripción médica'],['tolerancia','Tolerancia'],['observaciones','Observaciones']] as [$field,$label])<div class="col-md-6"><label class="form-label">{{ $label }}</label><textarea class="form-control" rows="3" wire:model="{{ $field }}"></textarea></div>@endforeach</div></div>
        </div>
        <div class="clinical-form-actions"><button type="button" class="btn btn-outline-secondary" wire:click="resetForm">Cancelar</button><button class="btn btn-primary">Guardar ficha</button></div>
    </form>
</div>
@include('livewire.hemodialysis.partials.list', ['date' => fn($record) => $record->fecha_sesion?->format('Y-m-d').' · N° '.$record->numero_sesion, 'pdfRoute' => fn($record) => route('hemodialysis.sessions.pdf', $record)])
@include('livewire.hemodialysis.partials.footer')
