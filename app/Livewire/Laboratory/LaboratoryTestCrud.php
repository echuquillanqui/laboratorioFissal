<?php

namespace App\Livewire\Laboratory;

use App\Models\Laboratory\LaboratoryArea;
use App\Models\Laboratory\LaboratoryTest;
use Livewire\Component;
use Livewire\WithPagination;

class LaboratoryTestCrud extends Component
{
    use WithPagination;

    public string $search = '';
    public ?int $areaFilter = null;

    public function render()
    {
        return view('livewire.laboratory.laboratory-test-crud', [
            'areas' => LaboratoryArea::query()->orderBy('nombre')->get(['id', 'nombre']),
            'tests' => LaboratoryTest::query()
                ->with('area:id,nombre')
                ->when($this->search !== '', fn ($q) => $q->whereAny(['codigo', 'nombre'], 'like', "%{$this->search}%"))
                ->when($this->areaFilter, fn ($q) => $q->where('laboratory_area_id', $this->areaFilter))
                ->orderBy('nombre')
                ->paginate(10),
        ]);
    }
}
