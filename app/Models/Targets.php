<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;

class Targets extends Model
{
    use HasFactory;

    protected $table = 'targets';
    protected $fillable = ['user_id', 'year', 'quarter', 'target', 'confirmed', 'product_id', 'code'];

    // Relationship with the User model
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }


//    public function product()
//    {
//        return $this->belongsTo(Product::class);
//    }
//

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    public function targetMonths()
    {
        return $this->hasMany(TargetMonth::class);


    }
}
