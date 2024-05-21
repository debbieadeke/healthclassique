<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeneralUploads extends Model
{

    protected $table = 'general_uploads';
    use HasFactory;

    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id');
    }
}
