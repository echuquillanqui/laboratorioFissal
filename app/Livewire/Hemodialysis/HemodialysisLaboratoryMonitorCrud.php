<?php

namespace App\Livewire\Hemodialysis;

use App\Models\Hemodialysis\HemodialysisLaboratoryMonitor;
use App\Models\Hemodialysis\HemodialysisSession;
use App\Models\Laboratory\LaboratoryOrder;
use Livewire\Component;
use Livewire\WithPagination;

class HemodialysisLaboratoryMonitorCrud extends Component
{
    use Concerns, WithPagination;

    public string $search = '';
    public ?int $editingId = null, $patientId = null, $hemodialysis_session_id = null, $laboratory_order_id = null;
    public string $fecha_muestra = '';
    public ?string $observacion = null;
    public string $estado = 'borrador';
    public array $results = [];

    public function mount(): void { $this->fecha_muestra = now()->toDateString(); $this->results = $this->blankResults(); }

    public function save(): void
    {
        abort_unless($this->can($this->editingId ? 'editar_hd' : 'crear_hd'), 403);

        $this->validate(['patientId' => 'required|exists:patients,id', 'fecha_muestra' => 'required|date']);
        $monitor = HemodialysisLaboratoryMonitor::updateOrCreate(['id' => $this->editingId], [
            'patient_id' => $this->patientId,
            'hemodialysis_session_id' => $this->hemodialysis_session_id,
            'laboratory_order_id' => $this->laboratory_order_id,
            'created_by' => $this->userId(),
            'fecha_muestra' => $this->fecha_muestra,
            'observacion' => $this->observacion,
            'estado' => $this->estado,
        ]);
        $monitor->results()->delete();
        foreach ($this->results as $row) {
            if (blank($row['nombre_prueba'] ?? null) && blank($row['valor'] ?? null)) { continue; }
            $monitor->results()->create([
                'nombre_prueba' => $row['nombre_prueba'] ?? '',
                'valor' => $row['valor'] ?? null,
                'unidad' => $row['unidad'] ?? null,
                'valor_referencia' => $row['valor_referencia'] ?? null,
                'alerta' => (bool) ($row['alerta'] ?? false),
            ]);
        }
        $this->resetForm(); session()->flash('status', 'Monitoreo de laboratorio guardado correctamente.');
    }

    public function addResult(): void { $this->results[] = ['nombre_prueba' => '', 'valor' => '', 'unidad' => '', 'valor_referencia' => '', 'alerta' => false]; }
    public function removeResult(int $index): void { unset($this->results[$index]); $this->results = array_values($this->results); }

    public function edit(int $id): void
    {
        $record = HemodialysisLaboratoryMonitor::with('results')->findOrFail($id);
        $this->editingId = $record->id; $this->patientId = $record->patient_id; $this->loadPatientSummary($record->patient_id);
        $this->hemodialysis_session_id = $record->hemodialysis_session_id; $this->laboratory_order_id = $record->laboratory_order_id;
        $this->fecha_muestra = $record->fecha_muestra?->toDateString() ?? now()->toDateString(); $this->observacion = $record->observacion; $this->estado = $record->estado;
        $this->results = $record->results->map(fn($r) => ['nombre_prueba' => $r->nombre_prueba, 'valor' => $r->valor, 'unidad' => $r->unidad, 'valor_referencia' => $r->valor_referencia, 'alerta' => (bool) $r->alerta])->all() ?: $this->blankResults();
    }

    public function delete(int $id): void { abort_unless($this->can('eliminar_hd'), 403); HemodialysisLaboratoryMonitor::findOrFail($id)->delete(); }

    public function resetForm(): void
    {
        $this->reset(['editingId','patientId','patientSummary','hemodialysis_session_id','laboratory_order_id','observacion']);
        $this->fecha_muestra = now()->toDateString(); $this->estado = 'borrador'; $this->results = $this->blankResults(); $this->resetValidation();
    }

    private function blankResults(): array
    {
        $tests = [
            ['Función renal', 'Urea', 'mg/dL'],
            ['Función renal', 'Creatinina', 'mg/dL'],
            ['Función renal', 'Proteinuria 24 h', 'mg/24 h'],
            ['Función renal', 'Albuminuria 24 h', 'mg/24 h'],
            ['Función renal', 'CKD-EPI 2021', 'mL/min/1.73 m²'],
            ['Hemograma/coagulación/reactantes', 'Leucocitos', 'cel/mm³'],
            ['Hemograma/coagulación/reactantes', 'Abastonados', '%'],
            ['Hemograma/coagulación/reactantes', 'Plaquetas', 'cel/mm³'],
            ['Hemograma/coagulación/reactantes', 'PCR', 'mg/L'],
            ['Hemograma/coagulación/reactantes', 'VSG', 'mm/h'],
            ['Hemograma/coagulación/reactantes', 'Procalcitonina', 'ng/mL'],
            ['Hemograma/coagulación/reactantes', 'TTPa', 's'],
            ['Hemograma/coagulación/reactantes', 'TP - INR', 'INR'],
            ['Perfil de anemia', 'Hemoglobina', 'g/dL'],
            ['Perfil de anemia', 'Hematocrito', '%'],
            ['Perfil de anemia', 'Ferritina', 'ng/mL'],
            ['Perfil de anemia', 'Sat. transferrina', '%'],
            ['Perfil de anemia', 'B12', 'pg/mL'],
            ['Perfil de anemia', 'Ácido fólico', 'ng/mL'],
            ['Perfil de anemia', 'Reticulocitos', '%'],
            ['Medio interno/oxigenación', 'pH', ''],
            ['Medio interno/oxigenación', 'pCO₂', 'mmHg'],
            ['Medio interno/oxigenación', 'HCO₃', 'mEq/L'],
            ['Medio interno/oxigenación', 'K⁺', 'mEq/L'],
            ['Medio interno/oxigenación', 'Na⁺', 'mEq/L'],
            ['Medio interno/oxigenación', 'Cl⁻', 'mEq/L'],
            ['Medio interno/oxigenación', 'PaO₂/FiO₂', ''],
            ['Metabolismo mineral/óseo', 'Calcio', 'mg/dL'],
            ['Metabolismo mineral/óseo', 'Fósforo', 'mg/dL'],
            ['Metabolismo mineral/óseo', 'PTHi', 'pg/mL'],
            ['Metabolismo mineral/óseo', 'Fosfatasa alcalina', 'U/L'],
            ['Metabolismo mineral/óseo', 'Magnesio', 'mg/dL'],
            ['Función hepática', 'TGP/ALT', 'U/L'],
            ['Función hepática', 'TGO/AST', 'U/L'],
            ['Función hepática', 'Proteínas totales', 'g/dL'],
            ['Función hepática', 'Albúmina', 'g/dL'],
            ['Función hepática', 'Bilirrubinas totales', 'mg/dL'],
            ['Función hepática', 'Bilirrubina directa', 'mg/dL'],
            ['Perfil lipídico', 'Colesterol total', 'mg/dL'],
            ['Perfil lipídico', 'LDL', 'mg/dL'],
            ['Perfil lipídico', 'HDL', 'mg/dL'],
            ['Perfil lipídico', 'TG', 'mg/dL'],
            ['Autoinmunidad', 'ANA', ''],
            ['Autoinmunidad', 'ANCA P', ''],
            ['Autoinmunidad', 'ANCA C', ''],
            ['Autoinmunidad', 'Factor reumatoideo', 'UI/mL'],
            ['Autoinmunidad', 'Coombs directo', ''],
            ['Examen de orina', 'Leucocitos', 'cel/campo'],
            ['Examen de orina', 'Hematíes', 'cel/campo'],
            ['Examen de orina', 'Hematíes dismórficos', ''],
            ['Examen de orina', 'Cilindros hemáticos', ''],
            ['Examen de orina', 'Cilindros céreos', ''],
            ['Examen de orina', 'Albuminuria (cualitativa)', ''],
            ['Examen de orina', 'Glucosuria', ''],
            ['Diuresis', 'Diuresis diaria', 'mL/24 h'],
        ];

        return array_map(
            fn (array $test) => [
                'nombre_prueba' => $test[1].' · '.$test[0],
                'valor' => '',
                'unidad' => $test[2],
                'valor_referencia' => '',
                'alerta' => false,
            ],
            $tests
        );
    }

    public function render()
    {
        return view('livewire.hemodialysis.laboratory-monitor-crud', [
            'records' => HemodialysisLaboratoryMonitor::with('patient','session','results')->when($this->search !== '', fn($q) => $q->whereHas('patient', fn($p) => $p->where('nombres_apellidos','like',"%{$this->search}%")->orWhere('dni','like',"%{$this->search}%")->orWhere('numero_historia','like',"%{$this->search}%")))->latest()->paginate(10),
            'sessions' => $this->patientId ? HemodialysisSession::where('patient_id', $this->patientId)->latest()->get() : collect(),
            'orders' => $this->patientId ? LaboratoryOrder::where('patient_id', $this->patientId)->latest()->get() : collect(),
        ])->layout('layouts.app');
    }
}
