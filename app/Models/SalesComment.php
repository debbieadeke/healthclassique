<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesComment extends Model
{
    use HasFactory;
    protected $fillable = [
        'comment',
        'user_id',
        'sales_call_id'
    ];

    public function salesCall()
    {
        return $this->belongsTo(SalesCall::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

}
