<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PercentageRange extends Model
{
    use HasFactory;
    protected $table = 'percentage_ranges';
    protected $fillable = [
        'percentage_range'
    ];
}
