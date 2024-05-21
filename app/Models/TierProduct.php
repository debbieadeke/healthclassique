<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TierProduct extends Model
{
    use HasFactory;
    protected $table = 'tier_products';
    protected $fillable = [
        'team_id', 'tier', 'products'
    ];



    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function getProductsAttribute()
    {
        // Decode JSON array of product IDs
        $productIds = json_decode($this->attributes['products'], true); // true parameter to decode as associative array
        // Check if productIds is not null
        if ($productIds !== null) {
            // Retrieve products with the decoded IDs
            return Product::whereIn('id', $productIds)->get();
        }
        // Return null if productIds is null
        return null;
    }


}
