<div class="container-fluid px-3 px-lg-5">
    <div class="panel-card shadow-sm border-0 overflow-hidden">
        <div class="card-body p-4 p-lg-5">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center gap-3 mb-4">
                <div>
                    <span class="eyebrow text-primary">Laboratorio</span>
                    <h2 class="h3 fw-bold mb-1">Perfiles de laboratorio</h2>
                    <p class="text-secondary mb-0">Administra perfiles con múltiples pruebas y controla su estado operativo.</p>
                </div>
                <span class="status-chip px-3 py-2">
                    <i class="fa-solid fa-layer-group me-2"></i>
                    {{ $profiles->total() }} perfiles registrados
                </span>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-lg-7">
                    <label class="form-label fw-semibold">Buscar perfil</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="fa-solid fa-magnifying-glass text-secondary"></i></span>
                        <input type="text" class="form-control" wire:model.live.debounce.300ms="search" placeholder="Nombre del perfil">
                    </div>
                </div>
                <div class="col-lg-5 d-flex align-items-end">
                    <button class="btn btn-outline-secondary rounded-pill px-4" wire:click="$refresh" type="button">
                        <i class="fa-solid fa-rotate-right me-2"></i>Actualizar listado
                    </button>
                </div>
            </div>

            <div class="card border-0 shadow-sm bg-light-subtle mb-4">
                <div class="card-body p-3 p-lg-4">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Nombre</label>
                            <input class="form-control" wire:model="nombre" placeholder="Ej. Perfil metabólico">
                            @error('nombre') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                        <div class="col-md-5">
                            <label class="form-label fw-semibold">Pruebas del perfil</label>
                            <select multiple class="form-select" wire:model="test_ids" style="min-height: 120px;">
                                @foreach($tests as $t)
                                    <option value="{{ $t->id }}">{{ $t->nombre }}</option>
                                @endforeach
                            </select>
                            @error('test_ids') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <button class="btn btn-primary w-100" wire:click="save" type="button">
                                <i class="fa-solid fa-floppy-disk me-2"></i>
                                {{ $editingId ? 'Actualizar perfil' : 'Guardar perfil' }}
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table align-middle table-hover mb-3">
                    <thead>
                        <tr>
                            <th>Perfil</th>
                            <th>Pruebas asociadas</th>
                            <th class="text-center">Estado</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($profiles as $p)
                        <tr>
                            <td class="fw-semibold">{{ $p->nombre }}</td>
                            <td>
                                <small class="text-secondary">{{ $p->tests->pluck('nombre')->join(', ') }}</small>
                            </td>
                            <td class="text-center">
                                @if($p->deleted_at)
                                    <span class="badge text-bg-secondary">Archivado</span>
                                @else
                                    <span class="badge text-bg-success">Activo</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <button wire:click="edit({{ $p->id }})" class="btn btn-sm btn-outline-primary rounded-pill me-2" type="button">Editar</button>
                                @if($p->deleted_at)
                                    <button wire:click="restore({{ $p->id }})" class="btn btn-sm btn-outline-success rounded-pill" type="button">Restaurar</button>
                                @else
                                    <button wire:click="delete({{ $p->id }})" class="btn btn-sm btn-outline-danger rounded-pill" type="button">Archivar</button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-secondary py-4">No se encontraron perfiles con los filtros actuales.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center justify-content-lg-end">
                {{ $profiles->links() }}
            </div>
        </div>
    </div>
</div>
