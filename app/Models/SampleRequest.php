<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class SampleRequest extends Model
{
    use HasFactory;
    protected $table = 'sample_requests';
    protected $fillable = [
        'user_id',
        'product_id',
        'quantity_requested',
        'quantity_approved',
        'quantity_issued',
        'notes',
        'comments',
        'issued_by',
        'approved_by'
    ];


    public function product(): HasOne
    {
        return $this->hasOne(Product::class, 'id', 'product_id');
    }

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function sampleInventory()
    {
        return $this->hasOne(SampleInventory::class, 'product_id', 'product_id');
    }

    public function userSampleInventory()
    {
        return $this->hasOne(UserSampleInventory::class, 'user_id', )
            ->where('product_id', $this->product_id); // Additional condition
    }

    public function userInventory()
    {
        return $this->hasOne(UserSampleInventory::class, 'user_id', 'user_id')
            ->whereColumn('user_sample_inventory.product_id', '=', 'sample_requests.product_id');
    }
}
