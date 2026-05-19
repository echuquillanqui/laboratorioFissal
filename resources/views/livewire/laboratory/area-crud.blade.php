<div class="card border-0 shadow-sm overflow-hidden laboratory-area-card">
    <div class="card-body p-0">
        <div class="bg-primary bg-gradient text-white p-4 p-md-5">
            <div class="d-flex flex-wrap justify-content-between align-items-start gap-3">
                <div>
                    <p class="text-uppercase fw-semibold small mb-2 opacity-75">Catálogo de laboratorio</p>
                    <h4 class="mb-2">Gestión de áreas</h4>
                    <p class="mb-0 opacity-75">Administra las áreas clínicas con un flujo claro, visual y profesional.</p>
                </div>
                <button class="btn btn-light text-primary fw-semibold px-3" wire:click="openCreateModal">
                    <i class="bi bi-plus-circle me-2"></i>Nueva área
                </button>
            </div>
        </div>

        <div class="p-4 p-md-5 pt-4">
            <div class="row g-3 mb-4 align-items-center">
                <div class="col-lg-8">
                    <label class="form-label text-muted small text-uppercase fw-semibold mb-2">Búsqueda inteligente</label>
                    <div class="input-group input-group-lg shadow-sm">
                        <span class="input-group-text bg-white border-end-0"><i class="bi bi-search"></i></span>
                        <input
                            class="form-control border-start-0"
                            placeholder="Buscar por nombre o descripción..."
                            wire:model.live.debounce.250ms="search"
                        >
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="bg-light rounded-4 p-3 h-100 border">
                        <p class="text-muted small mb-1">Resultados visibles</p>
                        <h5 class="mb-0">{{ $areas->total() }} áreas</h5>
                    </div>
                </div>
            </div>

            <div class="table-responsive border rounded-4 overflow-hidden">
                <table class="table align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Área</th>
                            <th>Descripción</th>
                            <th>Estado</th>
                            <th class="text-end pe-4">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($areas as $a)
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center gap-3">
                                        <span class="rounded-circle d-inline-flex align-items-center justify-content-center bg-primary-subtle text-primary" style="width: 2.2rem; height: 2.2rem;">
                                            <i class="bi bi-beaker"></i>
                                        </span>
                                        <div>
                                            <p class="fw-semibold mb-0">{{ $a->nombre }}</p>
                                            <small class="text-muted">ID #{{ $a->id }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td class="text-muted">{{ $a->descripcion ?: 'Sin descripción registrada.' }}</td>
                                <td>
                                    @if($a->deleted_at)
                                        <span class="badge rounded-pill text-bg-danger">Eliminado</span>
                                    @elseif($a->estado)
                                        <span class="badge rounded-pill text-bg-success">Activo</span>
                                    @else
                                        <span class="badge rounded-pill text-bg-secondary">Inactivo</span>
                                    @endif
                                </td>
                                <td class="text-end pe-4">
                                    <div class="btn-group">
                                        <button class="btn btn-sm btn-outline-primary" wire:click="edit({{ $a->id }})">
                                            <i class="bi bi-pencil-square me-1"></i>Editar
                                        </button>
                                        @if($a->deleted_at)
                                            <button class="btn btn-sm btn-warning" wire:click="restore({{ $a->id }})">
                                                <i class="bi bi-arrow-counterclockwise me-1"></i>Restaurar
                                            </button>
                                        @else
                                            <button class="btn btn-sm btn-outline-danger" wire:click="delete({{ $a->id }})">
                                                <i class="bi bi-trash me-1"></i>Eliminar
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-5">
                                    <div class="d-flex flex-column align-items-center text-muted">
                                        <i class="bi bi-inboxes fs-1 mb-2"></i>
                                        <p class="mb-1 fw-semibold">No hay áreas con ese criterio</p>
                                        <small>Prueba con otro término de búsqueda o crea una nueva área.</small>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $areas->links() }}
            </div>
        </div>

        @if($showModal)
            <div class="modal fade show d-block" tabindex="-1" style="background: rgba(15, 23, 42, .55); backdrop-filter: blur(3px);">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
                        <div class="modal-header bg-light border-0 px-4 py-3">
                            <h5 class="modal-title fw-semibold">{{ $editingId ? 'Editar área' : 'Registrar nueva área' }}</h5>
                            <button type="button" class="btn-close" wire:click="closeModal"></button>
                        </div>
                        <div class="modal-body px-4 py-4">
                            <div class="row g-3">
                                <div class="col-md-8">
                                    <label class="form-label fw-semibold">Nombre del área</label>
                                    <input class="form-control form-control-lg" wire:model="nombre" placeholder="Ej. Hematología">
                                    @error('nombre') <small class="text-danger d-block mt-1">{{ $message }}</small> @enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-semibold">Estado</label>
                                    <select class="form-select form-select-lg" wire:model="estado">
                                        <option value="1">Activo</option>
                                        <option value="0">Inactivo</option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-semibold">Descripción clínica</label>
                                    <textarea class="form-control" rows="4" wire:model="descripcion" placeholder="Describe el alcance clínico y operativo del área"></textarea>
                                    @error('descripcion') <small class="text-danger d-block mt-1">{{ $message }}</small> @enderror
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer border-0 px-4 pb-4">
                            <button type="button" class="btn btn-light" wire:click="closeModal">Cancelar</button>
                            <button type="button" class="btn btn-primary px-4" wire:click="save">Guardar cambios</button>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
