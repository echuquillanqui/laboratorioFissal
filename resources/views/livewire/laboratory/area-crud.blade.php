<div class="card shadow-sm border-0">
    <div class="card-body p-4">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
            <div>
                <h5 class="mb-1">Áreas de laboratorio</h5>
                <p class="text-muted mb-0">Diseño moderno con búsqueda interactiva y gestión por modal.</p>
            </div>
            <button class="btn btn-primary" wire:click="openCreateModal">+ Nueva área</button>
        </div>

        <div class="row g-2 mb-3">
            <div class="col-md-8">
                <input class="form-control" placeholder="Buscar por nombre de área..." wire:model.live.debounce.250ms="search">
            </div>
        </div>

        <div class="table-responsive">
            <table class="table align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Área</th>
                        <th>Descripción</th>
                        <th>Estado</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($areas as $a)
                        <tr>
                            <td class="fw-semibold">{{ $a->nombre }}</td>
                            <td class="text-muted">{{ $a->descripcion ?: '—' }}</td>
                            <td>
                                @if($a->deleted_at)
                                    <span class="badge bg-danger">Eliminado</span>
                                @elseif($a->estado)
                                    <span class="badge bg-success">Activo</span>
                                @else
                                    <span class="badge bg-secondary">Inactivo</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <button class="btn btn-sm btn-outline-primary" wire:click="edit({{ $a->id }})">Editar</button>
                                @if($a->deleted_at)
                                    <button class="btn btn-sm btn-warning" wire:click="restore({{ $a->id }})">Restaurar</button>
                                @else
                                    <button class="btn btn-sm btn-outline-danger" wire:click="delete({{ $a->id }})">Eliminar</button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-4">No se encontraron áreas con ese criterio.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $areas->links() }}

        @if($showModal)
            <div class="modal fade show d-block" tabindex="-1" style="background: rgba(15, 23, 42, .5);">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content border-0 shadow-lg">
                        <div class="modal-header">
                            <h5 class="modal-title">{{ $editingId ? 'Editar área' : 'Registrar área' }}</h5>
                            <button type="button" class="btn-close" wire:click="closeModal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row g-3">
                                <div class="col-md-7">
                                    <label class="form-label">Nombre</label>
                                    <input class="form-control" wire:model="nombre" placeholder="Ej. Hematología">
                                    @error('nombre') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                                <div class="col-md-5">
                                    <label class="form-label">Estado</label>
                                    <select class="form-select" wire:model="estado">
                                        <option value="1">Activo</option>
                                        <option value="0">Inactivo</option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Descripción</label>
                                    <textarea class="form-control" rows="3" wire:model="descripcion" placeholder="Detalle clínico del área"></textarea>
                                    @error('descripcion') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" wire:click="closeModal">Cancelar</button>
                            <button type="button" class="btn btn-primary" wire:click="save">Guardar cambios</button>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
