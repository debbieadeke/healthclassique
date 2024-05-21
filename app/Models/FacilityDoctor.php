<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FacilityDoctor extends Model
{
    use HasFactory;


    protected $table = 'facility_doctor';

    protected $casts = [
        'client_id' => 'json',
    ];
}
