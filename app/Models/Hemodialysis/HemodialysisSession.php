<?php

namespace App\Models\Hemodialysis;

use App\Models\Patient;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class HemodialysisSession extends Model
{
    use SoftDeletes;

    protected $fillable = ['patient_id', 'hemodialysis_admission_id', 'created_by', 'numero_sesion', 'fecha_sesion', 'hora_inicio', 'hora_fin', 'peso_pre', 'peso_post', 'acceso_vascular', 'tipo_cateter', 'horas_hd', 'ultrafiltracion_ml', 'anticoagulacion', 'flujo_sanguineo', 'flujo_dializado', 'dializador', 'hipotension_intradialisis', 'arritmias', 'complicaciones', 'prescripcion_medica', 'tolerancia', 'observaciones', 'estado'];

    protected $casts = [
        'fecha_sesion' => 'date',
        'peso_pre' => 'decimal:2',
        'peso_post' => 'decimal:2',
        'horas_hd' => 'decimal:2',
        'hipotension_intradialisis' => 'boolean',
        'arritmias' => 'boolean',
    ];

    public function patient(): BelongsTo { return $this->belongsTo(Patient::class); }
    public function admission(): BelongsTo { return $this->belongsTo(HemodialysisAdmission::class, 'hemodialysis_admission_id'); }
    public function creator(): BelongsTo { return $this->belongsTo(User::class, 'created_by'); }
    public function nursingNotes(): HasMany { return $this->hasMany(HemodialysisNursingNote::class); }
    public function laboratoryMonitors(): HasMany { return $this->hasMany(HemodialysisLaboratoryMonitor::class); }
}
