<?php
namespace App\Models\Laboratory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
class LaboratoryPackage extends Model
{
    use SoftDeletes;
    protected $fillable = ['nombre', 'descripcion', 'precio', 'estado'];
    protected $casts = ['estado' => 'boolean', 'precio' => 'decimal:2'];
    public function items(): HasMany { return $this->hasMany(LaboratoryPackageItem::class); }
}
