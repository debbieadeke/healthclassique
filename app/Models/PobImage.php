<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PobImage extends Model
{
    use HasFactory;
    protected $table = 'pob_uploads';
    protected $fillable = ['user_id', 'customer_name','customer_code','notes', 'image_source', 'pxn_image_url'];
    public function salesperson()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
