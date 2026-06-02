@include('livewire.hemodialysis.partials.header', ['title' => 'Evaluación Médica de Ingreso'])
<div class="clinical-form-card p-4 p-lg-5">
    <form wire:submit="save">
        <div class="row g-3">
            <div class="col-lg-6">@include('livewire.hemodialysis.partials.patient-selector', ['id' => 'evaluation-patient']) @include('livewire.hemodialysis.partials.patient-summary')</div>
            <div class="col-md-3"><label class="form-label">Fecha evaluación</label><input type="datetime-local" class="form-control" wire:model="fecha_evaluacion"></div>
            <div class="col-md-3"><label class="form-label">Ingreso relacionado</label><select class="form-select" wire:model="hemodialysis_admission_id"><option value="">Sin vincular</option>@foreach($admissions as $admission)<option value="{{ $admission->id }}">{{ $admission->fecha_ingreso_hd?->format('Y-m-d') }} · {{ $admission->diagnostico_renal }}</option>@endforeach</select></div>
            <div class="col-md-3"><label class="form-label">Estado</label><select class="form-select" wire:model="estado"><option>borrador</option><option>cerrado</option></select></div>
        </div>
        <div class="row g-3 mt-2">
            @foreach([['motivo_ingreso','Motivo de ingreso'],['examen_fisico','Examen físico'],['diagnosticos','Diagnósticos'],['plan_tratamiento','Plan de tratamiento'],['riesgos','Riesgos'],['indicaciones_medicas','Indicaciones médicas']] as [$field,$label])
                <div class="col-md-6"><label class="form-label">{{ $label }}</label><textarea class="form-control" rows="4" wire:model="{{ $field }}"></textarea></div>
            @endforeach
        </div>
        <div class="clinical-form-actions"><button type="button" class="btn btn-outline-secondary" wire:click="resetForm">Cancelar</button><button class="btn btn-primary">Guardar evaluación</button></div>
    </form>
</div>
@include('livewire.hemodialysis.partials.list', ['date' => fn($record) => $record->fecha_evaluacion?->format('Y-m-d H:i'), 'pdfRoute' => fn($record) => route('hemodialysis.evaluations.pdf', $record)])
@include('livewire.hemodialysis.partials.footer')
