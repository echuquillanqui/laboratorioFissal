<?php

namespace App\Models\Hemodialysis;

use App\Models\Patient;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class HemodialysisNursingNote extends Model
{
    use SoftDeletes;

    protected $fillable = ['patient_id', 'hemodialysis_session_id', 'nurse_id', 'fecha_nota', 'subjetivo', 'objetivo', 'analisis', 'plan', 'intervencion', 'evaluacion', 'estado'];

    protected $casts = ['fecha_nota' => 'datetime'];

    public function patient(): BelongsTo { return $this->belongsTo(Patient::class); }
    public function session(): BelongsTo { return $this->belongsTo(HemodialysisSession::class, 'hemodialysis_session_id'); }
    public function nurse(): BelongsTo { return $this->belongsTo(User::class, 'nurse_id'); }
}
