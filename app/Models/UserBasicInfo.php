<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class UserBasicInfo extends Model
{
    use HasFactory;
    protected $table = 'basic_user_information';
    protected $fillable = [
        'user_id',
        'image',
        'employee_id',
        'national_id',
        'birthday',
        'gender',
        'address',
        'county',
        'town',
        'phone',
        'date_joined'
    ];

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
