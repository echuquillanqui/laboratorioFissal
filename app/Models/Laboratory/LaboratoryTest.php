<?php

namespace App\Models\Laboratory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class LaboratoryTest extends Model
{
    use SoftDeletes;

    protected $fillable = ['laboratory_area_id', 'codigo', 'nombre', 'descripcion', 'unidad_medida', 'tipo_dato', 'valor_minimo', 'valor_maximo', 'valor_minimo_m', 'valor_maximo_m', 'valor_minimo_f', 'valor_maximo_f', 'valor_alerta_minimo', 'valor_alerta_maximo', 'tiene_opciones', 'estado'];

    protected $casts = ['tiene_opciones' => 'boolean', 'estado' => 'boolean'];

    public function area(): BelongsTo { return $this->belongsTo(LaboratoryArea::class, 'laboratory_area_id'); }
    public function options(): HasMany { return $this->hasMany(LaboratoryTestOption::class); }
    public function profiles(): BelongsToMany { return $this->belongsToMany(LaboratoryProfile::class, 'laboratory_profile_test'); }
}
