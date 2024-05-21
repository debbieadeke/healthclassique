<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class SampleSlip extends Model
{
    use HasFactory;
    protected $table = 'sample_slips';
    protected $fillable = [
        'sales_call_id',
        'image_source',
        'sample_slip_image_url',
        'user_id',
        'sales_call_detail_id'
    ];

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
