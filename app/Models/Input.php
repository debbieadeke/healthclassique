<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\Product;

class Input extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * The products that use that ingredient
     */
    public function products() : BelongsToMany
    {
        return $this->belongsToMany(Product::class);
    }
}
