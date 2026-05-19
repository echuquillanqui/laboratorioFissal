<?php
namespace App\Models\Laboratory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
class LaboratoryOrderItem extends Model
{
    public $timestamps = false;
    protected $fillable = ['laboratory_order_id', 'laboratory_test_id', 'origen'];
    public function order(): BelongsTo { return $this->belongsTo(LaboratoryOrder::class, 'laboratory_order_id'); }
    public function test(): BelongsTo { return $this->belongsTo(LaboratoryTest::class, 'laboratory_test_id'); }
    public function result(): HasOne { return $this->hasOne(LaboratoryResult::class); }
}
