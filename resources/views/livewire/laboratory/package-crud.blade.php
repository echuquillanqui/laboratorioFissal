<div class="container-fluid px-0">
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body p-4">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
                <div>
                    <h4 class="mb-1 fw-bold text-primary">Gestión de Paquetes de Laboratorio</h4>
                    <p class="text-muted mb-0">Crea paquetes profesionales con pruebas y perfiles en un solo lugar.</p>
                </div>
                <div class="w-100 w-md-auto" style="max-width: 360px;">
                    <div class="input-group">
                        <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                        <input class="form-control" wire:model.live="search" placeholder="Buscar paquete por nombre...">
                    </div>
                </div>
            </div>

            <div class="card border-0 bg-light-subtle">
                <div class="card-body p-4">
                    <h6 class="text-uppercase text-secondary fw-semibold mb-3">Datos del paquete</h6>
                    <div class="row g-3">
                        <div class="col-lg-4">
                            <label class="form-label fw-semibold">Nombre del paquete</label>
                            <input class="form-control" wire:model="nombre" placeholder="Ej. Perfil Renal Integral">
                        </div>

                        <div class="col-lg-2 col-md-4">
                            <label class="form-label fw-semibold">Precio (USD)</label>
                            <input type="number" step="0.01" class="form-control" wire:model="precio" placeholder="0.00">
                        </div>

                        <div class="col-lg-3 col-md-6">
                            <label class="form-label fw-semibold">Pruebas incluidas</label>
                            <div class="row g-2 mb-2">
                                <div class="col-7">
                                    <input class="form-control" wire:model.live.debounce.300ms="testSearch" placeholder="Buscar prueba...">
                                </div>
                                <div class="col-5">
                                    <select class="form-select" wire:model.live="selectedAreaId">
                                        <option value="">Todas</option>
                                        @foreach($areas as $area)
                                            <option value="{{ $area->id }}">{{ $area->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="border rounded p-2 bg-white" style="max-height: 160px; overflow:auto;">
                                @forelse($tests as $t)
                                    <button type="button" class="btn btn-sm {{ in_array($t->id, $test_ids, true) ? 'btn-primary' : 'btn-outline-secondary' }} rounded-pill me-1 mb-1" wire:click="toggleTest({{ $t->id }})">
                                        {{ $t->nombre }}
                                    </button>
                                @empty
                                    <small class="text-muted">Sin resultados.</small>
                                @endforelse
                            </div>
                            <div class="mt-2">
                                @foreach($selectedTests as $selected)
                                    <span class="badge text-bg-primary me-1">{{ $selected->nombre }} <button type="button" class="btn btn-sm text-white p-0 ms-1" wire:click="removeTest({{ $selected->id }})">×</button></span>
                                @endforeach
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-4">
                            <label class="form-label fw-semibold">Perfiles incluidos</label>
                            <select multiple class="form-select" wire:model="profile_ids" style="min-height: 140px;">
                                @foreach($profiles as $p)
                                    <option value="{{ $p->id }}">{{ $p->nombre }}</option>
                                @endforeach
                            </select>
                            <small class="text-muted">Puedes seleccionar varios perfiles.</small>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end mt-3">
                        <button class="btn btn-primary px-4" wire:click="save">
                            <i class="bi bi-check2-circle me-1"></i> Guardar paquete
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="p-4 border-bottom">
                <h6 class="mb-1 fw-semibold">Listado de paquetes</h6>
                <small class="text-muted">Visualiza, revisa y edita los paquetes disponibles.</small>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Paquete</th>
                            <th>Precio</th>
                            <th>Contenido</th>
                            <th class="text-end pe-4">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($packages as $p)
                            <tr>
                                <td class="ps-4 fw-semibold">{{ $p->nombre }}</td>
                                <td>${{ number_format((float) $p->precio, 2) }}</td>
                                <td>
                                    <span class="badge rounded-pill text-bg-info-subtle text-info-emphasis border">
                                        {{ $p->items->count() }} ítems
                                    </span>
                                </td>
                                <td class="text-end pe-4">
                                    <button wire:click="edit({{ $p->id }})" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-pencil-square me-1"></i> Editar
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-5 text-muted">
                                    No hay paquetes registrados todavía.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="p-3">
                {{ $packages->links() }}
            </div>
        </div>
    </div>
</div>
