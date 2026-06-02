@include('livewire.hemodialysis.partials.header', ['title' => 'Notas de Enfermería SOAPIE'])
<div class="clinical-form-card p-4 p-lg-5">
    <form wire:submit="save">
        <div class="row g-3">
            <div class="col-lg-6">@include('livewire.hemodialysis.partials.patient-selector', ['id' => 'nursing-patient']) @include('livewire.hemodialysis.partials.patient-summary')</div>
            <div class="col-md-3"><label class="form-label">Fecha nota</label><input type="datetime-local" class="form-control" wire:model="fecha_nota"></div>
            <div class="col-md-3"><label class="form-label">Sesión</label><select class="form-select" wire:model="hemodialysis_session_id"><option value="">Sin vincular</option>@foreach($sessions as $session)<option value="{{ $session->id }}">N° {{ $session->numero_sesion }} · {{ $session->fecha_sesion?->format('Y-m-d') }}</option>@endforeach</select></div>
            <div class="col-md-3"><label class="form-label">Estado</label><select class="form-select" wire:model="estado"><option>borrador</option><option>cerrado</option></select></div>
        </div>
        <div class="row g-3 mt-2">
            @foreach([['subjetivo','S - Subjetivo'],['objetivo','O - Objetivo'],['analisis','A - Análisis'],['plan','P - Plan'],['intervencion','I - Intervención'],['evaluacion','E - Evaluación']] as [$field,$label])
                <div class="col-md-6"><label class="form-label">{{ $label }}</label><textarea class="form-control" rows="4" wire:model="{{ $field }}"></textarea></div>
            @endforeach
        </div>
        <div class="clinical-form-actions"><button type="button" class="btn btn-outline-secondary" wire:click="resetForm">Cancelar</button><button class="btn btn-primary">Guardar nota</button></div>
    </form>
</div>
@include('livewire.hemodialysis.partials.list', ['date' => fn($record) => $record->fecha_nota?->format('Y-m-d H:i'), 'pdfRoute' => fn($record) => route('hemodialysis.nursing-notes.pdf', $record)])
@include('livewire.hemodialysis.partials.footer')
