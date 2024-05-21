<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;

class ProductSample extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_type',
        'salescall_or_detail_id',
        'product_id',
        'sample_batch_id',
        'quantity',
        'sales_call_detail_id'
    ];

    public function product(): HasOne
    {
        return $this->hasOne(Product::class, 'id', 'product_id');
    }
}
