<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class SampleInventory extends Model
{
    use HasFactory;
    protected $table = 'samples_inventory';
    protected $fillable = ['product_id', 'quantity'];

    public function product(): HasOne
    {
        return $this->hasOne(Product::class, 'id', 'product_id');
    }
}
