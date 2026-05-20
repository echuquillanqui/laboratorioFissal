<div class="container-fluid px-0">
    <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
        <div class="card-body p-4 p-lg-5 bg-white">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-4">
                <div>
                    <h4 class="mb-1 fw-bold text-primary">Gestión de órdenes de laboratorio</h4>
                    <p class="text-muted mb-0">Administra exámenes, estado y datos de cada orden en un solo flujo.</p>
                </div>
                <span class="badge rounded-pill text-bg-dark px-3 py-2">{{ $orders->total() }} órdenes</span>
            </div>

            <div class="border rounded-4 p-3 p-md-4 bg-body-tertiary mb-4">
                <h5 class="mb-3 fw-semibold">Nueva orden</h5>
                <div class="row g-3">
                    <div class="col-12 col-lg-3"><label class="form-label small text-muted">Paciente</label><select class="form-select" wire:model="patient_id"><option value="">Seleccionar paciente</option>@foreach($patients as $p)<option value="{{ $p->id }}">{{ $p->nombres_apellidos }}</option>@endforeach</select></div>
                    <div class="col-12 col-lg-3"><label class="form-label small text-muted">Pruebas</label><select multiple class="form-select" wire:model="test_ids">@foreach($tests as $t)<option value="{{ $t->id }}">{{ $t->nombre }}</option>@endforeach</select></div>
                    <div class="col-12 col-lg-3"><label class="form-label small text-muted">Perfiles</label><select multiple class="form-select" wire:model="profile_ids">@foreach($profiles as $p)<option value="{{ $p->id }}">{{ $p->nombre }}</option>@endforeach</select></div>
                    <div class="col-12 col-lg-3"><label class="form-label small text-muted">Paquetes</label><select multiple class="form-select" wire:model="package_ids">@foreach($packages as $p)<option value="{{ $p->id }}">{{ $p->nombre }}</option>@endforeach</select></div>
                </div>
                <div class="mt-3 d-flex justify-content-end"><button class="btn btn-primary px-4" wire:click="save">Crear orden</button></div>
            </div>

            <div class="mb-3 position-relative">
                <label class="form-label small text-muted">Buscar orden</label>
                <input type="text" class="form-control form-control-lg" wire:model.live.debounce.250ms="orderSearch" placeholder="ID, nombre o documento del paciente">
                @if($orderSearch !== '' && $orderSuggestions->isNotEmpty())
                    <div class="autocomplete-results d-block" style="position: absolute; width: 100%; z-index: 2;">
                        @foreach($orderSuggestions as $suggestion)
                            <button type="button" class="autocomplete-item" wire:click="$set('orderSearch','{{ addslashes($suggestion->nombres_apellidos) }}')">
                                {{ $suggestion->nombres_apellidos }} <small class="text-muted">({{ $suggestion->numero_documento }})</small>
                            </button>
                        @endforeach
                    </div>
                @endif
            </div>

            @if($editOrderId)
                <div class="card border-primary-subtle bg-primary-subtle mb-4">
                    <div class="card-body">
                        <h6 class="fw-bold text-primary">Editando orden #{{ $editOrderId }}</h6>
                        <div class="row g-3">
                            <div class="col-md-3"><label class="form-label small">Paciente</label><select class="form-select" wire:model="editPatientId"><option value="">Seleccionar paciente</option>@foreach($patients as $p)<option value="{{ $p->id }}">{{ $p->nombres_apellidos }}</option>@endforeach</select></div>
                            <div class="col-md-3"><label class="form-label small">Estado</label><select class="form-select" wire:model="editEstado">@foreach($statuses as $status)<option value="{{ $status }}">{{ $status }}</option>@endforeach</select></div>
                            <div class="col-md-6"><label class="form-label small">Observación</label><input class="form-control" wire:model="editObservacion" placeholder="Observaciones de la orden"></div>
                            <div class="col-12">
                                <label class="form-label small">Exámenes de la orden (puedes agregar o quitar)</label>
                                <select multiple class="form-select" wire:model="editTestIds">
                                    @foreach($tests as $t)
                                        <option value="{{ $t->id }}">{{ $t->nombre }}</option>
                                    @endforeach
                                </select>
                                <div class="d-flex flex-wrap gap-2 mt-2">
                                    @foreach($tests->whereIn('id', collect($editTestIds)->map(fn($id)=>(int)$id)->all()) as $selectedTest)
                                        <span class="badge bg-white text-dark border">{{ $selectedTest->nombre }}
                                            <button type="button" class="btn btn-sm p-0 ms-1" wire:click="removeEditItem({{ $selectedTest->id }})">×</button>
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end gap-2 mt-3">
                            <button class="btn btn-success" wire:click="updateOrder">Guardar cambios</button>
                            <button class="btn btn-outline-secondary" wire:click="cancelEdit">Cancelar</button>
                        </div>
                    </div>
                </div>
            @endif

            <div class="table-responsive border rounded-4 shadow-sm">
                <table class="table align-middle mb-0">
                    <thead class="table-dark"><tr><th>#</th><th>Paciente</th><th>Estado</th><th>Exámenes</th><th class="text-end">Acciones</th></tr></thead>
                    <tbody>
                    @forelse($orders as $o)
                        <tr>
                            <td class="fw-semibold">#{{ $o->id }}</td>
                            <td>{{ $o->patient?->nombres_apellidos }}</td>
                            <td><span class="badge rounded-pill bg-info-subtle text-info-emphasis">{{ $o->estado }}</span></td>
                            <td>
                                <span class="badge bg-secondary-subtle text-secondary-emphasis mb-1">{{ $o->items->count() }} exámenes</span>
                                <div class="small text-muted">{{ $o->items->take(3)->pluck('test.nombre')->filter()->implode(', ') }}{{ $o->items->count() > 3 ? '...' : '' }}</div>
                            </td>
                            <td class="text-end d-flex gap-2 justify-content-end">
                                <button class="btn btn-sm btn-outline-primary" wire:click="startEdit({{ $o->id }})">Editar orden</button>
                                <button class="btn btn-sm btn-outline-warning" wire:click="openStatusModal({{ $o->id }})">Cambiar estado</button>
                                <button class="btn btn-sm btn-outline-danger" wire:click="deleteOrder({{ $o->id }})" wire:confirm="¿Seguro que deseas eliminar esta orden?">Eliminar</button>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center py-4 text-muted">No hay órdenes registradas.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">{{ $orders->links() }}</div>
        </div>
    </div>

    @if($showStatusModal)
        <div class="position-fixed top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center" style="background: rgba(5,10,25,.55); z-index: 1080;">
            <div class="card border-0 shadow-lg" style="width: min(95vw, 420px);">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-1">Actualizar estado</h5>
                    <p class="text-muted small mb-3">Orden #{{ $statusOrderId }}</p>
                    <label class="form-label">Estado</label>
                    <select class="form-select" wire:model="statusEstado">@foreach($statuses as $status)<option value="{{ $status }}">{{ $status }}</option>@endforeach</select>
                    <div class="d-flex justify-content-end gap-2 mt-4">
                        <button class="btn btn-outline-secondary" wire:click="closeStatusModal">Cancelar</button>
                        <button class="btn btn-primary" wire:click="saveStatus">Guardar</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
