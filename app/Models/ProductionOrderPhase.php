<?php

namespace App\Models;

use App\Models\ProductionOrderPhaseDetail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductionOrderPhase extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    // protected $fillable = [
    //     'production_setting_id',
    //     'phase_id',
    // ];
    protected $guarded = [];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
    ];

    public function productionorderphasedetails(): HasMany
    {
        return $this->hasMany(ProductionOrderPhaseDetail::class, 'id', 'production_order_phase_id');

    }

    public function productionorder():BelongsTo
    {
        return $this->belongsTo(ProductionOrder::class,'production_order_id');
    }
}
