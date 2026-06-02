<?php

namespace App\Models;

use App\Models\Hemodialysis\HemodialysisAdmission;
use App\Models\Hemodialysis\HemodialysisLaboratoryMonitor;
use App\Models\Hemodialysis\HemodialysisMedicalEvaluation;
use App\Models\Hemodialysis\HemodialysisNursingNote;
use App\Models\Hemodialysis\HemodialysisSession;
use App\Models\Laboratory\LaboratoryOrder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Patient extends Model
{
    protected $fillable = [
        'nombres_apellidos',
        'dni',
        'fecha_ingreso',
        'fecha_nacimiento',
        'edad',
        'sexo',
        'codigo_unico',
        'numero_sesion',
        'regimen',
        'numero_historia',
        'direccion',
        'telefono',
    ];

    protected $casts = [
        'fecha_ingreso' => 'date',
        'fecha_nacimiento' => 'date',
        'edad' => 'integer',
        'numero_sesion' => 'integer',
    ];

    public function hemodialysisAdmissions(): HasMany
    {
        return $this->hasMany(HemodialysisAdmission::class);
    }

    public function hemodialysisMedicalEvaluations(): HasMany
    {
        return $this->hasMany(HemodialysisMedicalEvaluation::class);
    }

    public function hemodialysisSessions(): HasMany
    {
        return $this->hasMany(HemodialysisSession::class);
    }

    public function hemodialysisNursingNotes(): HasMany
    {
        return $this->hasMany(HemodialysisNursingNote::class);
    }

    public function hemodialysisLaboratoryMonitors(): HasMany
    {
        return $this->hasMany(HemodialysisLaboratoryMonitor::class);
    }

    public function laboratoryOrders(): HasMany
    {
        return $this->hasMany(LaboratoryOrder::class);
    }
}
