<?php
namespace App\Models\Laboratory;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
class LaboratoryOrder extends Model
{
    use SoftDeletes;
    protected $fillable = ['patient_id', 'user_id', 'fecha_orden', 'estado', 'observacion'];
    protected $casts = ['fecha_orden' => 'date'];
    public function patient(): BelongsTo { return $this->belongsTo(Patient::class); }
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function items(): HasMany { return $this->hasMany(LaboratoryOrderItem::class); }
}
