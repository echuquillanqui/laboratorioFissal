<?php

namespace App\Livewire\Hemodialysis;

use App\Models\Hemodialysis\HemodialysisAdmission;
use Livewire\Component;
use Livewire\WithPagination;

class HemodialysisAdmissionCrud extends Component
{
    use Concerns, WithPagination;

    public string $search = '';
    public string $viewMode = 'index';
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

    public function mount($record = null): void
    {
        if (request()->routeIs('hemodialysis.admissions.create')) {
            $this->viewMode = 'create';
            $this->fecha_ingreso_hd = now()->toDateString();

            return;
        }

        if (request()->routeIs('hemodialysis.admissions.edit')) {
            $this->viewMode = 'edit';
            $admission = $record instanceof HemodialysisAdmission
                ? $record
                : HemodialysisAdmission::findOrFail($record);

            $this->loadAdmission($admission);

            return;
        }

        $this->viewMode = 'index';
        $this->fecha_ingreso_hd = now()->toDateString();
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function save(): mixed
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

        session()->flash('status', $this->editingId
            ? 'Historia de ingreso actualizada correctamente.'
            : 'Historia de ingreso creada correctamente.');

        $this->resetForm();

        return $this->redirectRoute('hemodialysis.admissions.index', navigate: true);
    }

    public function delete(int $id): void
    {
        abort_unless($this->can('eliminar_hd'), 403);

        HemodialysisAdmission::findOrFail($id)->delete();
        session()->flash('status', 'Historia de ingreso eliminada correctamente.');
        $this->resetPage();
    }

    public function resetForm(): void
    {
        $this->reset(['editingId','patientId','patientSummary','procedencia','diagnostico_renal','etiologia','antecedentes','comorbilidades','acceso_vascular_inicial','indicacion_hd','observaciones']);
        $this->fecha_ingreso_hd = now()->toDateString();
        $this->estado = 'borrador';
        $this->resetValidation();
    }

    protected function loadAdmission(HemodialysisAdmission $record): void
    {
        $this->editingId = $record->id;
        $this->patientId = $record->patient_id;
        $this->loadPatientSummary($record->patient_id);
        $this->fecha_ingreso_hd = $record->fecha_ingreso_hd?->toDateString() ?? now()->toDateString();

        foreach (['procedencia','diagnostico_renal','etiologia','antecedentes','comorbilidades','acceso_vascular_inicial','indicacion_hd','observaciones','estado'] as $field) {
            $this->{$field} = $record->{$field};
        }
    }

    public function render()
    {
        $records = $this->viewMode === 'index'
            ? HemodialysisAdmission::with('patient')
                ->when($this->search !== '', fn ($q) => $q->whereHas('patient', fn ($p) => $p->where('nombres_apellidos', 'like', "%{$this->search}%")->orWhere('dni', 'like', "%{$this->search}%")->orWhere('numero_historia', 'like', "%{$this->search}%")))
                ->latest()
                ->paginate(10)
            : null;

        return view("livewire.hemodialysis.admissions.{$this->viewMode}", [
            'records' => $records,
        ])->layout('layouts.app');
    }
}
