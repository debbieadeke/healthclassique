<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\Input;

class InputBatch extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'input_id',
        'supplier_id',
        'buying_price',
        'selling_price',
        'date_supplied',
        'quantity_purchased',
        'quantity_remaining',
        'pack_size_id',
        'unit_of_measure_id'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'buying_price' => 'decimal:2',
        'selling_price' => 'decimal:2',
        'date_supplied' => 'datetime',
    ];

    public function input(): HasOne
    {
        return $this->hasOne(Input::class,'id', 'input_id');
    }

    public function supplier(): HasOne
    {
        return $this->hasOne(Supplier::class,'id', 'supplier_id');
    }
}
