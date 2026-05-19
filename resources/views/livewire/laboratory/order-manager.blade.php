<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
            <div>
                <h4 class="mb-1 fw-bold text-primary">Órdenes de laboratorio</h4>
                <p class="text-muted mb-0">Crea órdenes de forma rápida y mantén el control del estado de cada solicitud.</p>
            </div>
            <span class="badge rounded-pill bg-primary-subtle text-primary px-3 py-2">
                {{ $orders->total() }} órdenes registradas
            </span>
        </div>

        <div class="border rounded-4 p-3 p-md-4 bg-light-subtle mb-4">
            <div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-3">
                <h5 class="mb-0 fw-semibold">Nueva orden</h5>
                <small class="text-muted">Completa los campos y presiona <strong>Crear orden</strong></small>
            </div>

            <div class="row g-3">
                <div class="col-12 col-lg-3">
                    <label class="form-label small text-muted mb-1">Paciente</label>
                    <select class="form-select" wire:model="patient_id">
                        <option value="">Seleccionar paciente</option>
                        @foreach($patients as $p)
                            <option value="{{ $p->id }}">{{ $p->nombres_apellidos }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-lg-3">
                    <label class="form-label small text-muted mb-1">Pruebas individuales</label>
                    <select multiple class="form-select" wire:model="test_ids">
                        @foreach($tests as $t)
                            <option value="{{ $t->id }}">{{ $t->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-lg-3">
                    <label class="form-label small text-muted mb-1">Perfiles</label>
                    <select multiple class="form-select" wire:model="profile_ids">
                        @foreach($profiles as $p)
                            <option value="{{ $p->id }}">{{ $p->nombre }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 col-lg-3">
                    <label class="form-label small text-muted mb-1">Paquetes</label>
                    <select multiple class="form-select" wire:model="package_ids">
                        @foreach($packages as $p)
                            <option value="{{ $p->id }}">{{ $p->nombre }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="mt-3 d-flex justify-content-end">
                <button class="btn btn-primary px-4" wire:click="save">Crear orden</button>
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
            <h5 class="mb-0 fw-semibold">Órdenes recientes</h5>
            <small class="text-muted">Seguimiento en tiempo real del flujo de trabajo</small>
        </div>

        <div class="mb-3 position-relative">
            <label class="form-label small text-muted mb-1">Buscar orden (autocomplete)</label>
            <input type="text" class="form-control" wire:model.live.debounce.250ms="orderSearch"
                   placeholder="ID de orden, nombre o DNI del paciente">
            @if($orderSearch !== '' && $orderSuggestions->isNotEmpty())
                <div class="autocomplete-results d-block" style="position: absolute; width: 100%;">
                    @foreach($orderSuggestions as $suggestion)
                        <button type="button" class="autocomplete-item"
                                wire:click="$set('orderSearch','{{ addslashes($suggestion->nombres_apellidos) }}')">
                            {{ $suggestion->nombres_apellidos }}
                            <small class="text-muted">({{ $suggestion->numero_documento }})</small>
                        </button>
                    @endforeach
                </div>
            @endif
        </div>

        @if($editOrderId)
            <div class="alert alert-info d-flex flex-column flex-md-row gap-2 align-items-md-end justify-content-between">
                <div class="d-flex gap-2 align-items-end flex-wrap">
                    <div>
                        <label class="form-label small mb-1">Estado</label>
                        <select class="form-select" wire:model="editEstado">
                            @foreach($statuses as $status)
                                <option value="{{ $status }}">{{ $status }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="form-label small mb-1">Observación</label>
                        <input type="text" class="form-control" wire:model="editObservacion">
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-success btn-sm" wire:click="updateOrder">Guardar</button>
                    <button class="btn btn-outline-secondary btn-sm" wire:click="cancelEdit">Cancelar</button>
                </div>
            </div>
        @endif

        <div class="table-responsive border rounded-4">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Paciente</th>
                    <th>Estado</th>
                    <th>Items</th>
                    <th class="text-end">Acciones</th>
                </tr>
                </thead>
                <tbody>
                @forelse($orders as $o)
                    <tr>
                        <td class="fw-semibold">#{{ $o->id }}</td>
                        <td>{{ $o->patient?->nombres_apellidos }}</td>
                        <td><span class="badge rounded-pill bg-info-subtle text-info-emphasis">{{ $o->estado }}</span></td>
                        <td><span class="badge rounded-pill bg-secondary-subtle text-secondary-emphasis">{{ $o->items->count() }}</span></td>
                        <td class="text-end">
                            <button class="btn btn-sm btn-outline-primary" wire:click="startEdit({{ $o->id }})">Editar</button>
                            <button class="btn btn-sm btn-outline-danger" wire:click="deleteOrder({{ $o->id }})"
                                    wire:confirm="¿Seguro que deseas eliminar esta orden?">Eliminar</button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-muted">No hay órdenes registradas todavía.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-3">{{ $orders->links() }}</div>
    </div>
</div>
