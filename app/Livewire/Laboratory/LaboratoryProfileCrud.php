<?php
namespace App\Livewire\Laboratory;

use App\Models\Laboratory\LaboratoryArea;
use App\Models\Laboratory\LaboratoryProfile;
use App\Models\Laboratory\LaboratoryTest;
use Livewire\Component;
use Livewire\WithPagination;

class LaboratoryProfileCrud extends Component
{
    use WithPagination;

    protected string $paginationTheme = 'bootstrap';

    public string $search = '';
    public string $testSearch = '';
    public ?int $selectedAreaId = null;
    public ?int $editingId = null;
    public string $nombre = '';
    public ?string $descripcion = null;
    public bool $estado = true;
    public array $test_ids = [];

    protected function rules(): array
    {
        return [
            'nombre' => 'required|max:150|unique:laboratory_profiles,nombre,' . ($this->editingId ?? 'NULL') . ',id',
            'test_ids' => 'required|array|min:1',
            'test_ids.*' => 'exists:laboratory_tests,id',
        ];
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatedTestSearch(): void
    {
        $this->resetPage();
    }

    public function updatedSelectedAreaId($value): void
    {
        $this->selectedAreaId = $value ? (int) $value : null;
        $this->resetPage();
    }

    public function toggleTest(int $testId): void
    {
        if (in_array($testId, $this->test_ids, true)) {
            $this->test_ids = array_values(array_diff($this->test_ids, [$testId]));
            return;
        }

        $this->test_ids[] = $testId;
        $this->test_ids = array_values(array_unique(array_map('intval', $this->test_ids)));
    }

    public function removeTest(int $testId): void
    {
        $this->test_ids = array_values(array_diff($this->test_ids, [$testId]));
    }

    public function clearTestFilters(): void
    {
        $this->testSearch = '';
        $this->selectedAreaId = null;
    }

    public function edit($id): void
    {
        $p = LaboratoryProfile::with('tests')->withTrashed()->findOrFail($id);
        $this->editingId = $id;
        $this->nombre = $p->nombre;
        $this->descripcion = $p->descripcion;
        $this->estado = (bool) $p->estado;
        $this->test_ids = $p->tests->pluck('id')->map(fn ($testId) => (int) $testId)->all();
    }

    public function save(): void
    {
        $this->validate();
        $p = LaboratoryProfile::updateOrCreate(['id' => $this->editingId], ['nombre' => $this->nombre, 'descripcion' => $this->descripcion, 'estado' => $this->estado]);
        $p->tests()->sync($this->test_ids);
        $this->reset(['editingId', 'nombre', 'descripcion', 'test_ids']);
        $this->estado = true;
    }

    public function delete($id): void
    {
        LaboratoryProfile::findOrFail($id)->delete();
    }

    public function restore($id): void
    {
        LaboratoryProfile::withTrashed()->findOrFail($id)->restore();
    }

    public function render()
    {
        $availableTests = LaboratoryTest::query()
            ->where('estado', true)
            ->with('area:id,nombre')
            ->when($this->selectedAreaId, fn ($q) => $q->where('laboratory_area_id', $this->selectedAreaId))
            ->when($this->testSearch !== '', fn ($q) => $q->where(function ($inner) {
                $inner->where('nombre', 'like', "%{$this->testSearch}%")
                    ->orWhere('codigo', 'like', "%{$this->testSearch}%");
            }))
            ->orderBy('nombre')
            ->get(['id', 'nombre', 'codigo', 'laboratory_area_id']);

        $selectedTests = empty($this->test_ids)
            ? collect()
            : LaboratoryTest::whereIn('id', $this->test_ids)->orderBy('nombre')->get(['id', 'nombre']);

        return view('livewire.laboratory.profile-crud', [
            'profiles' => LaboratoryProfile::withTrashed()
                ->with('tests:id,nombre')
                ->when($this->search !== '', fn ($q) => $q->where('nombre', 'like', "%{$this->search}%"))
                ->orderByDesc('id')
                ->paginate(10),
            'tests' => $availableTests,
            'selectedTests' => $selectedTests,
            'areas' => LaboratoryArea::where('estado', true)->orderBy('nombre')->get(['id', 'nombre']),
        ])->layout('layouts.app');
    }
}
