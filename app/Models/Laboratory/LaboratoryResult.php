<?php
namespace App\Models\Laboratory;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
class LaboratoryResult extends Model
{
    protected $fillable = ['laboratory_order_item_id', 'resultado_texto', 'resultado_numerico', 'resultado_opcion', 'observacion', 'validado_por', 'fecha_validacion', 'estado'];
    protected $casts = ['fecha_validacion' => 'datetime'];
    public function orderItem(): BelongsTo { return $this->belongsTo(LaboratoryOrderItem::class, 'laboratory_order_item_id'); }
    public function validator(): BelongsTo { return $this->belongsTo(User::class, 'validado_por'); }
}
