<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecordSales extends Model
{
    use HasFactory;
    protected $table = 'rep_sales_record';
    protected $fillable = [
        'user_id',
        'customer_code',
        'customer_name',
        'product_code',
        'product_name',
        'quantity',
        'date',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
