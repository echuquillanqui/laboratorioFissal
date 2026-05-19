<?php

namespace App\Models\Laboratory;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class LaboratoryArea extends Model
{
    use SoftDeletes;

    protected $fillable = ['nombre', 'descripcion', 'estado'];

    public function tests(): HasMany
    {
        return $this->hasMany(LaboratoryTest::class);
    }
}
