<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductionOrder extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    // protected $fillable = [
    //     'production_batch_id',
    //     'batch_quantity',
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

    public function productionsetting(): HasOne
    {
        return $this->hasOne(ProductionSetting::class, 'id', 'production_setting_id');
    }

    public function productionorderdetails(): HasMany
    {
        return $this->hasMany(ProductionOrderPhaseDetail::class, 'production_order_id', 'id');

    }

    // public function productionorderphases(): HasMany
    // {
    //     return $this->hasMany(ProductionOrderPhase::class, 'id', 'production_order_id');

    // }
    public function productionorderphases(): HasMany
    {
        return $this->hasMany(ProductionOrderPhase::class);

    }

    public function product()
    {
        return $this->belongsTo(Product::class,'product_id');

    }
}
