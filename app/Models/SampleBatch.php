<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class SampleBatch extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'product_id',
        'quantity_requested',
        'quantity_approved',
        'quantity_dispatched',
        'quantity_remaining',
        'quantity_invoiced',
        'quantity_issued',
        'Invoiced_by',
        'Issued_by',
        'approved_by',
        'invoiced_on',
        'issued_on',
        'dispatched_by',
        'approved_on',
        'dispatched_on'
    ];

    public function product(): HasOne
    {
        return $this->hasOne(Product::class, 'id', 'product_id');
    }

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }


}
