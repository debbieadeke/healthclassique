<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PharmacyUser extends Model
{
    use HasFactory;

    protected $table = 'pharmacy_user';

    protected $casts = [
        'product_ids' => 'json',
    ];
}
