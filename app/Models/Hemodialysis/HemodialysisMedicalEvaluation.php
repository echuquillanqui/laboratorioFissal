<?php

namespace App\Models\Hemodialysis;

use App\Models\Patient;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class HemodialysisMedicalEvaluation extends Model
{
    use SoftDeletes;

    protected $fillable = ['patient_id', 'hemodialysis_admission_id', 'evaluated_by', 'fecha_evaluacion', 'motivo_ingreso', 'examen_fisico', 'diagnosticos', 'plan_tratamiento', 'riesgos', 'indicaciones_medicas', 'estado'];

    protected $casts = ['fecha_evaluacion' => 'datetime'];

    public function patient(): BelongsTo { return $this->belongsTo(Patient::class); }
    public function admission(): BelongsTo { return $this->belongsTo(HemodialysisAdmission::class, 'hemodialysis_admission_id'); }
    public function evaluator(): BelongsTo { return $this->belongsTo(User::class, 'evaluated_by'); }
}
