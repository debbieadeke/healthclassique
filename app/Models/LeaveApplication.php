<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveApplication extends Model
{
    use HasFactory;

    protected $table = 'leave_applications';
    protected $fillable = ['user_id', 'leave_type','start_date','days', 'end_date', 'reason', 'status'];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
