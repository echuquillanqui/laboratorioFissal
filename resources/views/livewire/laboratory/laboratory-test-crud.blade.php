<div class="card shadow border-0 rounded-4 overflow-hidden">
    <div class="bg-primary bg-gradient text-white p-4">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
            <div>
                <h4 class="mb-1 fw-bold">Catálogo de exámenes individuales</h4>
                <p class="mb-0 opacity-75">Registro clínico con estructura por tipo de dato y opciones estandarizadas.</p>
            </div>
            <button class="btn btn-light text-primary fw-semibold px-4" wire:click="openCreateModal">
                + Nuevo examen
            </button>
        </div>
    </div>
    <div class="card-body p-4">
        <div class="row g-2 mb-3">
            <div class="col-md-7">
                <input class="form-control form-control-lg rounded-3" wire:model.live.debounce.300ms="search" placeholder="Buscar por código o nombre">
            </div>
            <div class="col-md-5">
                <select class="form-select form-select-lg rounded-3" wire:model.live="areaFilter">
                    <option value="">Todas las áreas</option>
                    @foreach($areas as $a)
                        <option value="{{ $a->id }}">{{ $a->nombre }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
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
                            <td><span class="badge rounded-pill bg-secondary-subtle text-secondary-emphasis text-uppercase px-3 py-2">{{ $t->tipo_dato }}</span></td>
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
                <div class="modal-dialog modal-xl modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">{{ $editingId ? 'Editar examen' : 'Nuevo examen individual' }}</h5>
                            <button type="button" class="btn-close" wire:click="closeModal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="alert alert-primary border-0 rounded-4 d-flex gap-2 align-items-start">
                                <span class="fw-bold">Sugerencia:</span>
                                <span>Si el tipo de dato es <strong>opción</strong>, registra explícitamente todas las respuestas válidas (ej. Grupo sanguíneo: A, B, O, AB; Factor RH: POSITIVO, NEGATIVO).</span>
                            </div>

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
                                <div class="col-md-3">
                                    <label class="form-label">Tipo de dato</label>
                                    <select class="form-select" wire:model.live="tipo_dato">
                                        <option>texto</option><option>numerico</option><option>opcion</option><option>booleano</option><option>multilinea</option>
                                    </select>
                                    @error('tipo_dato') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                                <div class="col-md-5">
                                    <label class="form-label">Área</label>
                                    <select class="form-select" wire:model="laboratory_area_id">
                                        <option value="">Seleccione área</option>
                                        @foreach($areas as $a)
                                            <option value="{{ $a->id }}">{{ $a->nombre }}</option>
                                        @endforeach
                                    </select>
                                    @error('laboratory_area_id') <small class="text-danger">{{ $message }}</small> @enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Unidad de medida (opcional)</label>
                                    <input class="form-control" wire:model="unidad_medida" placeholder="Ej. mg/dL, g/L, %">
                                </div>
                            </div>

                            @if($tipo_dato === 'numerico')
                                <div class="border rounded-4 p-3 mt-3 bg-light-subtle">
                                    <h6 class="mb-3">Valores referenciales y alertas</h6>
                                    <div class="row g-3">
                                        <div class="col-md-3">
                                            <label class="form-label">Valor mínimo de referencia</label>
                                            <input type="number" step="any" class="form-control" wire:model="valor_minimo" placeholder="Ej. 3.5">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Valor máximo de referencia</label>
                                            <input type="number" step="any" class="form-control" wire:model="valor_maximo" placeholder="Ej. 5.5">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Alerta mínima</label>
                                            <input type="number" step="any" class="form-control" wire:model="valor_alerta_minimo" placeholder="Ej. 3.0">
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label">Alerta máxima</label>
                                            <input type="number" step="any" class="form-control" wire:model="valor_alerta_maximo" placeholder="Ej. 6.0">
                                        </div>
                                    </div>
                                    <p class="small text-muted mt-2 mb-0">
                                        Los valores de alerta permiten identificar resultados críticos fuera del rango esperado.
                                    </p>
                                </div>
                            @endif

                            @if($tipo_dato === 'opcion')
                                <div class="border rounded-4 p-3 mt-3 bg-light-subtle">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <h6 class="mb-0">Opciones para este examen individual</h6>
                                        <button class="btn btn-sm btn-secondary" wire:click="addOption">+ Agregar opción</button>
                                    </div>

                                    <div class="mb-3">
                                        <p class="small text-muted mb-2">Plantillas rápidas para captura clínica:</p>
                                        <div class="d-flex flex-wrap gap-2">
                                            <button type="button" class="btn btn-sm btn-outline-primary" wire:click="applyOptionPreset('grupo_sanguineo')">Grupo sanguíneo (A, B, O, AB)</button>
                                            <button type="button" class="btn btn-sm btn-outline-primary" wire:click="applyOptionPreset('factor_rh')">Factor RH (POSITIVO / NEGATIVO)</button>
                                            <button type="button" class="btn btn-sm btn-outline-primary" wire:click="applyOptionPreset('reactivo_no_reactivo')">Reactivo / No reactivo</button>
                                        </div>
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
