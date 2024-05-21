<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EpimolMetrics extends Model
{
    use HasFactory;
    protected $table = 'ep_team_performance_metrics';
    protected $fillable = [
        'percentage',
        'team',
        'individual',
        'kPIs',
        'ep_tier_1_performance',
        'ep_tier_2_performance',
        'ep_tier_3_performance',
        'total_individual'
    ];
}
