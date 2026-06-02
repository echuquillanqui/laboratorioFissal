<?php

namespace App\Models\Hemodialysis;

use App\Models\Patient;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class HemodialysisAdmission extends Model
{
    use SoftDeletes;

    protected $fillable = ['patient_id', 'created_by', 'fecha_ingreso_hd', 'procedencia', 'diagnostico_renal', 'etiologia', 'antecedentes', 'comorbilidades', 'acceso_vascular_inicial', 'indicacion_hd', 'observaciones', 'estado'];

    protected $casts = ['fecha_ingreso_hd' => 'date'];

    public function patient(): BelongsTo { return $this->belongsTo(Patient::class); }
    public function creator(): BelongsTo { return $this->belongsTo(User::class, 'created_by'); }
    public function medicalEvaluations(): HasMany { return $this->hasMany(HemodialysisMedicalEvaluation::class); }
    public function sessions(): HasMany { return $this->hasMany(HemodialysisSession::class); }
}
