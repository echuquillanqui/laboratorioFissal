<?php

namespace App\Livewire\Hemodialysis;

use App\Models\Hemodialysis\HemodialysisAdmission;
use App\Models\Hemodialysis\HemodialysisMedicalEvaluation;
use Livewire\Component;
use Livewire\WithPagination;

class HemodialysisMedicalEvaluationCrud extends Component
{
    use Concerns, WithPagination;

    public string $search = '';
    public ?int $editingId = null;
    public ?int $patientId = null;
    public ?int $hemodialysis_admission_id = null;
    public string $fecha_evaluacion = '';
    public ?string $motivo_ingreso = null;
    public ?string $examen_fisico = null;
    public ?string $diagnosticos = null;
    public ?string $plan_tratamiento = null;
    public ?string $riesgos = null;
    public ?string $indicaciones_medicas = null;
    public string $estado = 'borrador';

    public function mount(): void { $this->fecha_evaluacion = now()->format('Y-m-d\TH:i'); }

    public function save(): void
    {
        abort_unless($this->can($this->editingId ? 'editar_hd' : 'crear_hd'), 403);

        $this->validate(['patientId' => 'required|exists:patients,id', 'fecha_evaluacion' => 'required|date']);
        HemodialysisMedicalEvaluation::updateOrCreate(['id' => $this->editingId], [
            'patient_id' => $this->patientId,
            'hemodialysis_admission_id' => $this->hemodialysis_admission_id,
            'evaluated_by' => $this->userId(),
            'fecha_evaluacion' => $this->fecha_evaluacion,
            'motivo_ingreso' => $this->motivo_ingreso,
            'examen_fisico' => $this->examen_fisico,
            'diagnosticos' => $this->diagnosticos,
            'plan_tratamiento' => $this->plan_tratamiento,
            'riesgos' => $this->riesgos,
            'indicaciones_medicas' => $this->indicaciones_medicas,
            'estado' => $this->estado,
        ]);
        $this->resetForm();
        session()->flash('status', 'Evaluación médica guardada correctamente.');
    }

    public function edit(int $id): void
    {
        $record = HemodialysisMedicalEvaluation::findOrFail($id);
        $this->editingId = $record->id;
        $this->patientId = $record->patient_id;
        $this->loadPatientSummary($record->patient_id);
        $this->hemodialysis_admission_id = $record->hemodialysis_admission_id;
        $this->fecha_evaluacion = $record->fecha_evaluacion?->format('Y-m-d\TH:i') ?? now()->format('Y-m-d\TH:i');
        foreach (['motivo_ingreso','examen_fisico','diagnosticos','plan_tratamiento','riesgos','indicaciones_medicas','estado'] as $field) { $this->{$field} = $record->{$field}; }
    }

    public function delete(int $id): void { abort_unless($this->can('eliminar_hd'), 403); HemodialysisMedicalEvaluation::findOrFail($id)->delete(); }

    public function resetForm(): void
    {
        $this->reset(['editingId','patientId','patientSummary','hemodialysis_admission_id','motivo_ingreso','examen_fisico','diagnosticos','plan_tratamiento','riesgos','indicaciones_medicas']);
        $this->fecha_evaluacion = now()->format('Y-m-d\TH:i');
        $this->estado = 'borrador';
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.hemodialysis.medical-evaluation-crud', [
            'records' => HemodialysisMedicalEvaluation::with('patient','admission')->when($this->search !== '', fn($q) => $q->whereHas('patient', fn($p) => $p->where('nombres_apellidos','like',"%{$this->search}%")->orWhere('dni','like',"%{$this->search}%")->orWhere('numero_historia','like',"%{$this->search}%")))->latest()->paginate(10),
            'admissions' => $this->patientId ? HemodialysisAdmission::where('patient_id', $this->patientId)->latest()->get() : collect(),
        ])->layout('layouts.app');
    }
}
