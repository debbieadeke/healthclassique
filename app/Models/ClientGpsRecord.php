<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClientGpsRecord extends Model
{
    use HasFactory;
    protected $table = 'clients_gps';
    protected $fillable = ['latitude', 'longitude','client_type','client_id','client_name','user_id'];
}
