<div class="card shadow-sm">
    <div class="card-header d-flex flex-wrap gap-2 justify-content-between">
        <input wire:model.live.debounce.300ms="search" class="form-control w-auto" placeholder="Buscar por código o nombre">
        <select wire:model.live="areaFilter" class="form-select w-auto">
            <option value="">Todas las áreas</option>
            @foreach($areas as $area)
                <option value="{{ $area->id }}">{{ $area->nombre }}</option>
            @endforeach
        </select>
    </div>

    <div class="table-responsive">
        <table class="table table-striped mb-0">
            <thead><tr><th>Código</th><th>Prueba</th><th>Área</th><th>Tipo</th></tr></thead>
            <tbody>
            @forelse($tests as $test)
                <tr>
                    <td>{{ $test->codigo }}</td>
                    <td>{{ $test->nombre }}</td>
                    <td>{{ $test->area?->nombre }}</td>
                    <td>{{ $test->tipo_dato }}</td>
                </tr>
            @empty
                <tr><td colspan="4" class="text-center">Sin registros.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="card-footer">{{ $tests->links() }}</div>
</div>
