<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ProductionOrderPhaseDetail extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'production_order_phase_id',
        'product_id',
        'percentage',
        'weight',
        'pack_size_id',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'percentage' => 'integer',
    ];

    public function input(): HasOne
    {
        return $this->hasOne(Input::class, 'id', 'input_id');
    }

    public function prodorder(): BelongsTo
    {
        return $this->belongsTo(ProductionOrder::class);
    }
}
