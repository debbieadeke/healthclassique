<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Experience extends Model
{
    use HasFactory;
    protected $table = 'experience';
    protected $fillable = [
        'user_id',
        'company_name',
        'job_position',
        'period_from',
        'period_to',
    ];
}
