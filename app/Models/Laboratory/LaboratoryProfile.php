<?php
namespace App\Models\Laboratory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
class LaboratoryProfile extends Model
{
    use SoftDeletes;
    protected $fillable = ['nombre', 'descripcion', 'estado'];
    protected $casts = ['estado' => 'boolean'];
    public function tests(): BelongsToMany { return $this->belongsToMany(LaboratoryTest::class, 'laboratory_profile_test'); }
}
