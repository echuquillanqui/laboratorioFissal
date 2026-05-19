<div class="card shadow-sm border-0">
    <div class="card-body p-4">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-3">
            <div>
                <h5 class="mb-1">Catálogo de exámenes individuales</h5>
                <p class="text-muted mb-0">Administra pruebas, tipos de dato y opciones de captura.</p>
            </div>
            <button class="btn btn-primary" wire:click="openCreateModal">
                + Nuevo examen
            </button>
        </div>

        <div class="row g-2 mb-3">
            <div class="col-md-7">
                <input class="form-control" wire:model.live.debounce.300ms="search" placeholder="Buscar por código o nombre">
            </div>
            <div class="col-md-5">
                <select class="form-select" wire:model.live="areaFilter">
                    <option value="">Todas las áreas</option>
                    @foreach($areas as $a)
                        <option value="{{ $a->id }}">{{ $a->nombre }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Código</th>
                        <th>Nombre</th>
                        <th>Tipo</th>
                        <th>Área</th>
                        <th>Estado</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tests as $t)
                        <tr>
                            <td class="fw-semibold">{{ $t->codigo }}</td>
                            <td>{{ $t->nombre }}</td>
                            <td><span class="badge bg-secondary text-uppercase">{{ $t->tipo_dato }}</span></td>
                            <td>{{ $t->area?->nombre }}</td>
                            <td>
                                @if($t->deleted_at)
                                    <span class="badge bg-warning text-dark">Inactivo</span>
                                @else
                                    <span class="badge bg-success">Activo</span>
                                @endif
                            </td>
                            <td class="text-end">
                                <button class="btn btn-sm btn-outline-primary" wire:click="edit({{ $t->id }})">Editar</button>
                                @if($t->deleted_at)
                                    <button class="btn btn-sm btn-warning" wire:click="restore({{ $t->id }})">Restaurar</button>
                                @else
                                    <button class="btn btn-sm btn-outline-danger" wire:click="delete({{ $t->id }})">Eliminar</button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">Sin resultados para los filtros aplicados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{ $tests->links() }}

        @if($showModal)
            <div class="modal fade show d-block" tabindex="-1" style="background: rgba(0,0,0,.45);">
                <div class="modal-dialog modal-lg modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">{{ $editingId ? 'Editar examen' : 'Nuevo examen individual' }}</h5>
                            <button type="button" class="btn-close" wire:click="closeModal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label">Código</label>
                                    <input class="form-control" wire:model="codigo" placeholder="Ej. HB001">
                                    @error('codigo') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                                <div class="col-md-8">
                                    <label class="form-label">Nombre</label>
                                    <input class="form-control" wire:model="nombre" placeholder="Nombre del examen">
                                    @error('nombre') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Tipo de dato</label>
                                    <select class="form-select" wire:model.live="tipo_dato">
                                        <option>texto</option><option>numerico</option><option>opcion</option><option>booleano</option><option>multilinea</option>
                                    </select>
                                    @error('tipo_dato') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                                <div class="col-md-8">
                                    <label class="form-label">Área</label>
                                    <select class="form-select" wire:model="laboratory_area_id">
                                        <option value="">Seleccione área</option>
                                        @foreach($areas as $a)
                                            <option value="{{ $a->id }}">{{ $a->nombre }}</option>
                                        @endforeach
                                    </select>
                                    @error('laboratory_area_id') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                            </div>

                            @if($tipo_dato === 'opcion')
                                <div class="border rounded p-3 mt-3 bg-light-subtle">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h6 class="mb-0">Opciones para este examen individual</h6>
                                        <button class="btn btn-sm btn-secondary" wire:click="addOption">+ Agregar opción</button>
                                    </div>
                                    @error('options') <small class="text-danger d-block mb-2">{{ $message }}</small> @enderror
                                    @foreach($options as $i => $o)
                                        <div class="row g-2 mb-2">
                                            <div class="col-md-5">
                                                <input class="form-control" wire:model="options.{{ $i }}.valor" placeholder="Valor interno">
                                                @error('options.'.$i.'.valor') <small class="text-danger">{{ $message }}</small> @enderror
                                            </div>
                                            <div class="col-md-5">
                                                <input class="form-control" wire:model="options.{{ $i }}.etiqueta" placeholder="Etiqueta visible">
                                                @error('options.'.$i.'.etiqueta') <small class="text-danger">{{ $message }}</small> @enderror
                                            </div>
                                            <div class="col-md-2 d-grid">
                                                <button class="btn btn-outline-danger" wire:click="removeOption({{ $i }})">Quitar</button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" wire:click="closeModal">Cancelar</button>
                            <button type="button" class="btn btn-primary" wire:click="save">Guardar examen</button>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
