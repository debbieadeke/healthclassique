<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveTaken extends Model
{
    use HasFactory;
    protected $table = 'leave_taken';

    protected $fillable = ['user_id', 'date', 'days_taken'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
