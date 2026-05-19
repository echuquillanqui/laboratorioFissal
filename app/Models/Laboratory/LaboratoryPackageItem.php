<?php
namespace App\Models\Laboratory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class LaboratoryPackageItem extends Model
{
    public $timestamps = false;
    protected $fillable = ['laboratory_package_id', 'tipo_item', 'reference_id'];
    public function package(): BelongsTo { return $this->belongsTo(LaboratoryPackage::class, 'laboratory_package_id'); }
}
