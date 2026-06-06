@include('livewire.hemodialysis.partials.header', ['title' => 'Historia Clínica de Ingreso a Hemodiálisis'])

<div class="clinical-form-card p-4 p-lg-5">
    <div class="d-flex flex-column flex-lg-row gap-3 justify-content-between align-items-lg-center mb-3">
        <div>
            <h2 class="h4 fw-bold mb-1">Ingresos registrados</h2>
            <p class="text-muted mb-0">Administra las historias clínicas de ingreso a hemodiálisis.</p>
        </div>
        <a class="btn btn-primary" href="{{ route('hemodialysis.admissions.create') }}" wire:navigate>
            <i class="fa-solid fa-plus me-1"></i> Nuevo ingreso
        </a>
    </div>

    @if (session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
    @endif

    <div class="d-flex justify-content-end mb-3">
        <input type="search" class="form-control" style="max-width: 360px" wire:model.live.debounce.350ms="search" placeholder="Buscar paciente, DNI o HC">
    </div>

    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
                <tr>
                    <th>Paciente</th>
                    <th>DNI</th>
                    <th>HC</th>
                    <th>Fecha</th>
                    <th>Estado</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($records as $record)
                    <tr>
                        <td>{{ $record->patient->nombres_apellidos ?? '—' }}</td>
                        <td>{{ $record->patient->dni ?? '—' }}</td>
                        <td>{{ $record->patient->numero_historia ?? '—' }}</td>
                        <td>{{ $record->fecha_ingreso_hd?->format('Y-m-d') ?? '—' }}</td>
                        <td><span class="badge text-bg-primary">{{ $record->estado }}</span></td>
                        <td class="text-end">
                            <a class="btn btn-sm btn-outline-secondary" target="_blank" href="{{ route('hemodialysis.admissions.pdf', $record) }}">PDF</a>
                            <a class="btn btn-sm btn-outline-primary" href="{{ route('hemodialysis.admissions.edit', $record) }}" wire:navigate>Editar</a>
                            <button class="btn btn-sm btn-outline-danger" wire:click="delete({{ $record->id }})" wire:confirm="¿Eliminar esta historia de ingreso?">Eliminar</button>
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

@include('livewire.hemodialysis.partials.footer')
