<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GPSRecord extends Model
{
    use HasFactory;
    protected $table = 'gps_records';
    protected $fillable = ['user_id','latitude', 'longitude', 'recorded_at','gps_type','client_type','Client_name','client_id','start_time','end_time'];
}
