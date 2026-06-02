<?php

namespace App\Livewire\Hemodialysis;

use App\Models\Hemodialysis\HemodialysisAdmission;
use Livewire\Component;
use Livewire\WithPagination;

class HemodialysisAdmissionCrud extends Component
{
    use Concerns, WithPagination;

    public string $search = '';
    public ?int $editingId = null;
    public ?int $patientId = null;
    public string $fecha_ingreso_hd = '';
    public ?string $procedencia = null;
    public ?string $diagnostico_renal = null;
    public ?string $etiologia = null;
    public ?string $antecedentes = null;
    public ?string $comorbilidades = null;
    public ?string $acceso_vascular_inicial = null;
    public ?string $indicacion_hd = null;
    public ?string $observaciones = null;
    public string $estado = 'borrador';

    public function mount(): void { $this->fecha_ingreso_hd = now()->toDateString(); }

    public function save(): void
    {
        abort_unless($this->can($this->editingId ? 'editar_hd' : 'crear_hd'), 403);

        $this->validate([
            'patientId' => 'required|exists:patients,id',
            'fecha_ingreso_hd' => 'required|date',
            'estado' => 'required|string|max:30',
        ]);

        HemodialysisAdmission::updateOrCreate(['id' => $this->editingId], [
            'patient_id' => $this->patientId,
            'created_by' => $this->userId(),
            'fecha_ingreso_hd' => $this->fecha_ingreso_hd,
            'procedencia' => $this->procedencia,
            'diagnostico_renal' => $this->diagnostico_renal,
            'etiologia' => $this->etiologia,
            'antecedentes' => $this->antecedentes,
            'comorbilidades' => $this->comorbilidades,
            'acceso_vascular_inicial' => $this->acceso_vascular_inicial,
            'indicacion_hd' => $this->indicacion_hd,
            'observaciones' => $this->observaciones,
            'estado' => $this->estado,
        ]);

        $this->resetForm();
        session()->flash('status', 'Historia de ingreso guardada correctamente.');
    }

    public function edit(int $id): void
    {
        $record = HemodialysisAdmission::findOrFail($id);
        $this->editingId = $record->id;
        $this->patientId = $record->patient_id;
        $this->loadPatientSummary($record->patient_id);
        $this->fecha_ingreso_hd = $record->fecha_ingreso_hd?->toDateString() ?? now()->toDateString();
        foreach (['procedencia','diagnostico_renal','etiologia','antecedentes','comorbilidades','acceso_vascular_inicial','indicacion_hd','observaciones','estado'] as $field) {
            $this->{$field} = $record->{$field};
        }
    }

    public function delete(int $id): void { abort_unless($this->can('eliminar_hd'), 403); HemodialysisAdmission::findOrFail($id)->delete(); }

    public function resetForm(): void
    {
        $this->reset(['editingId','patientId','patientSummary','procedencia','diagnostico_renal','etiologia','antecedentes','comorbilidades','acceso_vascular_inicial','indicacion_hd','observaciones']);
        $this->fecha_ingreso_hd = now()->toDateString();
        $this->estado = 'borrador';
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.hemodialysis.admission-crud', [
            'records' => HemodialysisAdmission::with('patient')
                ->when($this->search !== '', fn ($q) => $q->whereHas('patient', fn ($p) => $p->where('nombres_apellidos', 'like', "%{$this->search}%")->orWhere('dni', 'like', "%{$this->search}%")->orWhere('numero_historia', 'like', "%{$this->search}%")))
                ->latest()->paginate(10),
        ])->layout('layouts.app');
    }
}
