<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class YearlyLeave extends Model
{
    use HasFactory;
    protected $table = 'yearly_leave';
    protected $fillable = ['user_id', 'year', 'days_allocated'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
