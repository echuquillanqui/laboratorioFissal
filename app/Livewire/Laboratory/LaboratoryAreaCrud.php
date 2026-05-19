<?php

namespace App\Livewire\Laboratory;

use App\Models\Laboratory\LaboratoryArea;
use Livewire\Component;
use Livewire\WithPagination;

class LaboratoryAreaCrud extends Component
{
    use WithPagination;

    public string $search = '';
    public ?int $editingId = null;
    public string $nombre = '';
    public ?string $descripcion = null;
    public bool $estado = true;

    protected function rules(): array
    {
        return [
            'nombre' => 'required|string|max:120|unique:laboratory_areas,nombre,'.($this->editingId ?? 'NULL').',id',
            'descripcion' => 'nullable|string',
            'estado' => 'boolean',
        ];
    }

    public function edit(int $id): void { $a = LaboratoryArea::withTrashed()->findOrFail($id); $this->editingId=$id; $this->nombre=$a->nombre; $this->descripcion=$a->descripcion; $this->estado=(bool)$a->estado; }
    public function resetForm(): void { $this->reset(['editingId','nombre','descripcion']); $this->estado=true; }
    public function save(): void { $this->validate(); LaboratoryArea::updateOrCreate(['id'=>$this->editingId],[ 'nombre'=>$this->nombre,'descripcion'=>$this->descripcion,'estado'=>$this->estado]); $this->resetForm(); }
    public function delete(int $id): void { LaboratoryArea::findOrFail($id)->delete(); }
    public function restore(int $id): void { LaboratoryArea::withTrashed()->findOrFail($id)->restore(); }
    public function render() { return view('livewire.laboratory.area-crud',['areas'=>LaboratoryArea::withTrashed()->when($this->search!=='',fn($q)=>$q->where('nombre','like',"%{$this->search}%"))->orderByDesc('id')->paginate(10)]); }
}
