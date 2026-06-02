<div class="clinical-form-card p-4 mt-4">
    <div class="d-flex flex-column flex-lg-row gap-3 justify-content-between align-items-lg-center mb-3">
        <h2 class="h5 fw-bold mb-0">Registros</h2>
        <input type="search" class="form-control" style="max-width: 360px" wire:model.live.debounce.350ms="search" placeholder="Buscar paciente, DNI o HC">
    </div>
    <div class="table-responsive">
        <table class="table align-middle">
            <thead><tr><th>Paciente</th><th>DNI</th><th>HC</th><th>Fecha</th><th>Estado</th><th class="text-end">Acciones</th></tr></thead>
            <tbody>
                @forelse($records as $record)
                    <tr>
                        <td>{{ $record->patient->nombres_apellidos ?? '—' }}</td>
                        <td>{{ $record->patient->dni ?? '—' }}</td>
                        <td>{{ $record->patient->numero_historia ?? '—' }}</td>
                        <td>{{ $date($record) }}</td>
                        <td><span class="badge text-bg-primary">{{ $record->estado }}</span></td>
                        <td class="text-end">
                            <a class="btn btn-sm btn-outline-secondary" target="_blank" href="{{ $pdfRoute($record) }}">PDF</a>
                            <button class="btn btn-sm btn-outline-primary" wire:click="edit({{ $record->id }})">Editar</button>
                            <button class="btn btn-sm btn-outline-danger" wire:click="delete({{ $record->id }})" wire:confirm="¿Eliminar registro?">Eliminar</button>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">No hay registros.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    {{ $records->links() }}
</div>
