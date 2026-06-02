<?php

namespace App\Models\Hemodialysis;

use App\Models\Laboratory\LaboratoryOrder;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class HemodialysisLaboratoryMonitor extends Model
{
    use SoftDeletes;

    protected $fillable = ['patient_id', 'hemodialysis_session_id', 'laboratory_order_id', 'created_by', 'fecha_muestra', 'observacion', 'estado'];

    protected $casts = ['fecha_muestra' => 'date'];

    public function patient(): BelongsTo { return $this->belongsTo(Patient::class); }
    public function session(): BelongsTo { return $this->belongsTo(HemodialysisSession::class, 'hemodialysis_session_id'); }
    public function laboratoryOrder(): BelongsTo { return $this->belongsTo(LaboratoryOrder::class); }
    public function creator(): BelongsTo { return $this->belongsTo(User::class, 'created_by'); }
    public function results(): HasMany { return $this->hasMany(HemodialysisLaboratoryMonitorResult::class); }
}
