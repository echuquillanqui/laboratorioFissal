<?php

namespace App\Livewire\Hemodialysis;

use App\Models\Hemodialysis\HemodialysisAdmission;
use App\Models\Hemodialysis\HemodialysisSession;
use App\Models\Patient;
use Livewire\Component;
use Livewire\WithPagination;

class HemodialysisSessionCrud extends Component
{
    use Concerns, WithPagination;

    public string $search = '';
    public ?int $editingId = null;
    public ?int $patientId = null;
    public ?int $hemodialysis_admission_id = null;
    public int $numero_sesion = 1;
    public string $fecha_sesion = '';
    public ?string $hora_inicio = null, $hora_fin = null, $acceso_vascular = null, $tipo_cateter = null, $anticoagulacion = null, $flujo_sanguineo = null, $flujo_dializado = null, $dializador = null, $complicaciones = null, $prescripcion_medica = null, $tolerancia = null, $observaciones = null;
    public ?float $peso_pre = null, $peso_post = null, $horas_hd = null;
    public ?int $ultrafiltracion_ml = null;
    public bool $hipotension_intradialisis = false, $arritmias = false;
    public string $estado = 'borrador';

    public function mount(): void { $this->fecha_sesion = now()->toDateString(); }

    public function updatedPatientId($value): void
    {
        $this->loadPatientSummary((int) $value);
        $this->numero_sesion = ((int) HemodialysisSession::where('patient_id', $value)->max('numero_sesion')) + 1;
    }

    public function save(): void
    {
        abort_unless($this->can($this->editingId ? 'editar_hd' : 'crear_hd'), 403);

        $this->validate([
            'patientId' => 'required|exists:patients,id',
            'numero_sesion' => 'required|integer|min:1',
            'fecha_sesion' => 'required|date',
        ]);

        HemodialysisSession::updateOrCreate(['id' => $this->editingId], [
            'patient_id' => $this->patientId,
            'hemodialysis_admission_id' => $this->hemodialysis_admission_id,
            'created_by' => $this->userId(),
            'numero_sesion' => $this->numero_sesion,
            'fecha_sesion' => $this->fecha_sesion,
            'hora_inicio' => $this->hora_inicio,
            'hora_fin' => $this->hora_fin,
            'peso_pre' => $this->peso_pre,
            'peso_post' => $this->peso_post,
            'acceso_vascular' => $this->acceso_vascular,
            'tipo_cateter' => $this->tipo_cateter,
            'horas_hd' => $this->horas_hd,
            'ultrafiltracion_ml' => $this->ultrafiltracion_ml,
            'anticoagulacion' => $this->anticoagulacion,
            'flujo_sanguineo' => $this->flujo_sanguineo,
            'flujo_dializado' => $this->flujo_dializado,
            'dializador' => $this->dializador,
            'hipotension_intradialisis' => $this->hipotension_intradialisis,
            'arritmias' => $this->arritmias,
            'complicaciones' => $this->complicaciones,
            'prescripcion_medica' => $this->prescripcion_medica,
            'tolerancia' => $this->tolerancia,
            'observaciones' => $this->observaciones,
            'estado' => $this->estado,
        ]);

        Patient::whereKey($this->patientId)->update(['numero_sesion' => max($this->numero_sesion, (int) Patient::find($this->patientId)?->numero_sesion)]);
        $this->resetForm();
        session()->flash('status', 'Ficha de hemodiálisis guardada correctamente.');
    }

    public function edit(int $id): void
    {
        $record = HemodialysisSession::findOrFail($id);
        $this->editingId = $record->id;
        $this->patientId = $record->patient_id;
        $this->loadPatientSummary($record->patient_id);
        foreach (['hemodialysis_admission_id','numero_sesion','hora_inicio','hora_fin','acceso_vascular','tipo_cateter','anticoagulacion','flujo_sanguineo','flujo_dializado','dializador','complicaciones','prescripcion_medica','tolerancia','observaciones','peso_pre','peso_post','horas_hd','ultrafiltracion_ml','hipotension_intradialisis','arritmias','estado'] as $field) { $this->{$field} = $record->{$field}; }
        $this->fecha_sesion = $record->fecha_sesion?->toDateString() ?? now()->toDateString();
    }

    public function delete(int $id): void { abort_unless($this->can('eliminar_hd'), 403); HemodialysisSession::findOrFail($id)->delete(); }

    public function resetForm(): void
    {
        $this->reset(['editingId','patientId','patientSummary','hemodialysis_admission_id','hora_inicio','hora_fin','acceso_vascular','tipo_cateter','anticoagulacion','flujo_sanguineo','flujo_dializado','dializador','complicaciones','prescripcion_medica','tolerancia','observaciones','peso_pre','peso_post','horas_hd','ultrafiltracion_ml','hipotension_intradialisis','arritmias']);
        $this->numero_sesion = 1; $this->fecha_sesion = now()->toDateString(); $this->estado = 'borrador';
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.hemodialysis.session-crud', [
            'records' => HemodialysisSession::with('patient')->when($this->search !== '', fn($q) => $q->whereHas('patient', fn($p) => $p->where('nombres_apellidos','like',"%{$this->search}%")->orWhere('dni','like',"%{$this->search}%")->orWhere('numero_historia','like',"%{$this->search}%")))->latest()->paginate(10),
            'admissions' => $this->patientId ? HemodialysisAdmission::where('patient_id', $this->patientId)->latest()->get() : collect(),
        ])->layout('layouts.app');
    }
}
