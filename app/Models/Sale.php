<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;
use App\Models\User;

class Sale extends Model
{
    use HasFactory;
    protected $fillable = [
        'employee_name',
        'user_id',
        'product_id',
        'amount',
        'date',
        'customer_code',
        'customer_name',
        'product_code',
        'product_name',
        'processed',
        'quantity'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
