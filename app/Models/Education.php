<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Education extends Model
{
    use HasFactory;
    protected $table = 'educations';
    protected $fillable = [
        'user_id',
        'institution',
        'subject',
        'starting_date',
        'completion_date',
        'degree',
        'grade',
    ];
}
