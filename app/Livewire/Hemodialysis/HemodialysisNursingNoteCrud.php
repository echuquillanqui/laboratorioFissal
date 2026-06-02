<?php

namespace App\Livewire\Hemodialysis;

use App\Models\Hemodialysis\HemodialysisNursingNote;
use App\Models\Hemodialysis\HemodialysisSession;
use Livewire\Component;
use Livewire\WithPagination;

class HemodialysisNursingNoteCrud extends Component
{
    use Concerns, WithPagination;

    public string $search = '';
    public ?int $editingId = null, $patientId = null, $hemodialysis_session_id = null;
    public string $fecha_nota = '';
    public ?string $subjetivo = null, $objetivo = null, $analisis = null, $plan = null, $intervencion = null, $evaluacion = null;
    public string $estado = 'borrador';

    public function mount(): void { $this->fecha_nota = now()->format('Y-m-d\TH:i'); }

    public function save(): void
    {
        abort_unless($this->can($this->editingId ? 'editar_hd' : 'crear_hd'), 403);

        $this->validate(['patientId' => 'required|exists:patients,id', 'fecha_nota' => 'required|date']);
        HemodialysisNursingNote::updateOrCreate(['id' => $this->editingId], [
            'patient_id' => $this->patientId,
            'hemodialysis_session_id' => $this->hemodialysis_session_id,
            'nurse_id' => $this->userId(),
            'fecha_nota' => $this->fecha_nota,
            'subjetivo' => $this->subjetivo,
            'objetivo' => $this->objetivo,
            'analisis' => $this->analisis,
            'plan' => $this->plan,
            'intervencion' => $this->intervencion,
            'evaluacion' => $this->evaluacion,
            'estado' => $this->estado,
        ]);
        $this->resetForm(); session()->flash('status', 'Nota de enfermería guardada correctamente.');
    }

    public function edit(int $id): void
    {
        $record = HemodialysisNursingNote::findOrFail($id);
        $this->editingId = $record->id; $this->patientId = $record->patient_id; $this->loadPatientSummary($record->patient_id);
        $this->fecha_nota = $record->fecha_nota?->format('Y-m-d\TH:i') ?? now()->format('Y-m-d\TH:i');
        foreach (['hemodialysis_session_id','subjetivo','objetivo','analisis','plan','intervencion','evaluacion','estado'] as $field) { $this->{$field} = $record->{$field}; }
    }

    public function delete(int $id): void { abort_unless($this->can('eliminar_hd'), 403); HemodialysisNursingNote::findOrFail($id)->delete(); }

    public function resetForm(): void
    {
        $this->reset(['editingId','patientId','patientSummary','hemodialysis_session_id','subjetivo','objetivo','analisis','plan','intervencion','evaluacion']);
        $this->fecha_nota = now()->format('Y-m-d\TH:i'); $this->estado = 'borrador'; $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.hemodialysis.nursing-note-crud', [
            'records' => HemodialysisNursingNote::with('patient','session')->when($this->search !== '', fn($q) => $q->whereHas('patient', fn($p) => $p->where('nombres_apellidos','like',"%{$this->search}%")->orWhere('dni','like',"%{$this->search}%")->orWhere('numero_historia','like',"%{$this->search}%")))->latest()->paginate(10),
            'sessions' => $this->patientId ? HemodialysisSession::where('patient_id', $this->patientId)->latest()->get() : collect(),
        ])->layout('layouts.app');
    }
}
