<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncentiveMetrics extends Model
{
    use HasFactory;
    protected $table = 'incentive_metrics';
    protected $fillable = [
        'percentage',
        'kPIs',
        'ep_tier_1_performance',
        'ep_tier_2_performance',
        'ep_tier_3_performance',
        'total_individual'
    ];
}
