<?php

namespace App\Models\Hemodialysis;

use App\Models\Laboratory\LaboratoryTest;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HemodialysisLaboratoryMonitorResult extends Model
{
    protected $fillable = ['hemodialysis_laboratory_monitor_id', 'laboratory_test_id', 'nombre_prueba', 'valor', 'unidad', 'valor_referencia', 'alerta'];

    protected $casts = ['alerta' => 'boolean'];

    public function monitor(): BelongsTo { return $this->belongsTo(HemodialysisLaboratoryMonitor::class, 'hemodialysis_laboratory_monitor_id'); }
    public function laboratoryTest(): BelongsTo { return $this->belongsTo(LaboratoryTest::class); }
}
