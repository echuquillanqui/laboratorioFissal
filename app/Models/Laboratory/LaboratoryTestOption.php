<?php
namespace App\Models\Laboratory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class LaboratoryTestOption extends Model
{
    protected $fillable = ['laboratory_test_id', 'valor', 'etiqueta', 'orden'];
    public function test(): BelongsTo { return $this->belongsTo(LaboratoryTest::class, 'laboratory_test_id'); }
}
