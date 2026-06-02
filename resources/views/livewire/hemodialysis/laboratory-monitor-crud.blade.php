@include('livewire.hemodialysis.partials.header', ['title' => 'Monitoreo de Laboratorio'])
<div class="clinical-form-card p-4 p-lg-5">
    <form wire:submit="save">
        <div class="row g-3">
            <div class="col-lg-6">@include('livewire.hemodialysis.partials.patient-selector', ['id' => 'lab-monitor-patient']) @include('livewire.hemodialysis.partials.patient-summary')</div>
            <div class="col-md-2"><label class="form-label">Fecha muestra</label><input type="date" class="form-control" wire:model="fecha_muestra"></div>
            <div class="col-md-2"><label class="form-label">Sesión</label><select class="form-select" wire:model="hemodialysis_session_id"><option value="">Sin vincular</option>@foreach($sessions as $session)<option value="{{ $session->id }}">N° {{ $session->numero_sesion }} · {{ $session->fecha_sesion?->format('Y-m-d') }}</option>@endforeach</select></div>
            <div class="col-md-2"><label class="form-label">Orden lab.</label><select class="form-select" wire:model="laboratory_order_id"><option value="">Manual</option>@foreach($orders as $order)<option value="{{ $order->id }}">Orden #{{ $order->id }} · {{ $order->fecha_orden?->format('Y-m-d') }}</option>@endforeach</select></div>
            <div class="col-md-2"><label class="form-label">Estado</label><select class="form-select" wire:model="estado"><option>borrador</option><option>cerrado</option></select></div>
            <div class="col-12"><label class="form-label">Observación</label><textarea class="form-control" rows="2" wire:model="observacion"></textarea></div>
        </div>
        <div class="table-responsive mt-4">
            <table class="table align-middle">
                <thead><tr><th>Prueba</th><th>Valor</th><th>Unidad</th><th>Referencia</th><th>Alerta</th><th></th></tr></thead>
                <tbody>
                    @foreach($results as $i => $row)
                        <tr>
                            <td><input class="form-control" wire:model="results.{{ $i }}.nombre_prueba"></td>
                            <td><input class="form-control" wire:model="results.{{ $i }}.valor"></td>
                            <td><input class="form-control" wire:model="results.{{ $i }}.unidad"></td>
                            <td><input class="form-control" wire:model="results.{{ $i }}.valor_referencia"></td>
                            <td><input type="checkbox" class="form-check-input" wire:model="results.{{ $i }}.alerta"></td>
                            <td><button type="button" class="btn btn-sm btn-outline-danger" wire:click="removeResult({{ $i }})">Quitar</button></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <button type="button" class="btn btn-outline-primary" wire:click="addResult">Agregar resultado</button>
        </div>
        <div class="clinical-form-actions"><button type="button" class="btn btn-outline-secondary" wire:click="resetForm">Cancelar</button><button class="btn btn-primary">Guardar monitoreo</button></div>
    </form>
</div>
@include('livewire.hemodialysis.partials.list', ['date' => fn($record) => $record->fecha_muestra?->format('Y-m-d'), 'pdfRoute' => fn($record) => route('hemodialysis.laboratory-monitors.pdf', $record)])
@include('livewire.hemodialysis.partials.footer')
