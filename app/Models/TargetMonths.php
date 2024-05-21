<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TargetMonths extends Model
{
    use HasFactory;
    protected $table = 'target_months';
    protected $fillable = ['target_id', 'month', 'target'];

    public function target()
    {
        return $this->belongsTo(Targets::class);
    }
}
