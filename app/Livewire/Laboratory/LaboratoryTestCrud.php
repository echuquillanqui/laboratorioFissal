<?php

namespace App\Livewire\Laboratory;

use App\Enums\LaboratoryTypeDato;
use App\Models\Laboratory\LaboratoryArea;
use App\Models\Laboratory\LaboratoryTest;
use Livewire\Component;
use Livewire\WithPagination;

class LaboratoryTestCrud extends Component
{
    use WithPagination;

    public string $search = '';
    public ?int $areaFilter = null;
    public ?int $editingId = null;
    public ?int $laboratory_area_id = null;
    public string $codigo = '';
    public string $nombre = '';
    public ?string $descripcion = null;
    public ?string $unidad_medida = null;
    public string $tipo_dato = 'texto';
    public ?float $valor_minimo = null;
    public ?float $valor_maximo = null;
    public ?float $valor_alerta_minimo = null;
    public ?float $valor_alerta_maximo = null;
    public bool $estado = true;
    public array $options = [['valor' => '', 'etiqueta' => '']];
    public bool $showModal = false;

    protected function rules(): array
    {
        return [
            'laboratory_area_id' => 'required|exists:laboratory_areas,id',
            'codigo' => 'required|max:30|unique:laboratory_tests,codigo,' . ($this->editingId ?? 'NULL') . ',id',
            'nombre' => 'required|max:150',
            'tipo_dato' => 'required|in:' . implode(',', LaboratoryTypeDato::values()),
            'options' => $this->tipo_dato === 'opcion' ? 'required|array|min:1' : 'nullable|array',
            'options.*.valor' => $this->tipo_dato === 'opcion' ? 'required|string|max:120' : 'nullable|string|max:120',
            'options.*.etiqueta' => $this->tipo_dato === 'opcion' ? 'required|string|max:120' : 'nullable|string|max:120',
        ];
    }

    

    public function openCreateModal(): void
    {
        $this->closeModal();
        $this->showModal = true;
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function addOption(): void { $this->options[] = ['valor' => '', 'etiqueta' => '']; }
    public function removeOption(int $index): void { unset($this->options[$index]); $this->options = array_values($this->options); }
    public function applyOptionPreset(string $preset): void
    {
        $catalog = [
            'grupo_sanguineo' => ['A', 'B', 'O', 'AB'],
            'factor_rh' => ['POSITIVO', 'NEGATIVO'],
            'positivo_negativo' => ['POSITIVO', 'NEGATIVO'],
            'reactivo_no_reactivo' => ['REACTIVO', 'NO REACTIVO'],
        ];

        if (!array_key_exists($preset, $catalog)) {
            return;
        }

        $this->options = collect($catalog[$preset])
            ->map(fn(string $option) => ['valor' => $option, 'etiqueta' => $option])
            ->values()
            ->all();
    }

    public function edit(int $id): void
    {
        $t = LaboratoryTest::with('options')->withTrashed()->findOrFail($id);
        $this->editingId = $id;
        foreach (['laboratory_area_id','codigo','nombre','descripcion','unidad_medida','tipo_dato','valor_minimo','valor_maximo','valor_alerta_minimo','valor_alerta_maximo'] as $f) { $this->{$f} = $t->{$f}; }
        $this->estado = (bool) $t->estado;
        $this->options = $t->options->map(fn($o) => ['valor'=>$o->valor,'etiqueta'=>$o->etiqueta])->values()->all();
        if (!$this->options) $this->options = [['valor' => '', 'etiqueta' => '']];
        $this->showModal = true;
    }

    public function save(): void
    {
        $this->validate();
        $t = LaboratoryTest::updateOrCreate(['id' => $this->editingId], [
            'laboratory_area_id' => $this->laboratory_area_id,
            'codigo' => $this->codigo,
            'nombre' => $this->nombre,
            'descripcion' => $this->descripcion,
            'unidad_medida' => $this->unidad_medida,
            'tipo_dato' => $this->tipo_dato,
            'valor_minimo' => $this->valor_minimo,
            'valor_maximo' => $this->valor_maximo,
            'valor_alerta_minimo' => $this->valor_alerta_minimo,
            'valor_alerta_maximo' => $this->valor_alerta_maximo,
            'tiene_opciones' => $this->tipo_dato === 'opcion',
            'estado' => $this->estado,
        ]);
        $t->options()->delete();
        if ($this->tipo_dato === 'opcion') {
            foreach ($this->options as $i => $op) {
                if (($op['valor'] ?? '') !== '' && ($op['etiqueta'] ?? '') !== '') {
                    $t->options()->create(['valor' => $op['valor'], 'etiqueta' => $op['etiqueta'], 'orden' => $i + 1]);
                }
            }
        }
        $this->closeModal();
    }
    public function delete(int $id): void { LaboratoryTest::findOrFail($id)->delete(); }
    public function restore(int $id): void { LaboratoryTest::withTrashed()->findOrFail($id)->restore(); }
    public function resetForm(): void { $this->reset(['editingId','laboratory_area_id','codigo','nombre','descripcion','unidad_medida','valor_minimo','valor_maximo','valor_alerta_minimo','valor_alerta_maximo']); $this->tipo_dato='texto';$this->estado=true;$this->options=[['valor'=>'','etiqueta'=>'']]; }

    public function render()
    {
        return view('livewire.laboratory.laboratory-test-crud', [
            'areas' => LaboratoryArea::orderBy('nombre')->get(['id', 'nombre']),
            'tests' => LaboratoryTest::withTrashed()->with(['area:id,nombre'])->when($this->search !== '', fn($q) => $q->whereAny(['codigo', 'nombre'], 'like', "%{$this->search}%"))->when($this->areaFilter, fn($q) => $q->where('laboratory_area_id', $this->areaFilter))->orderByDesc('id')->paginate(10),
        ])->layout('layouts.app');
    }
}
