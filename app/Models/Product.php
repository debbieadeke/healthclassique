<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\User;
use App\Models\Input;
use App\Models\Category;
use App\Models\Team;
use App\Models\Targets;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'team_id',
        'price',
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }


    /**
     * The ingredients that belong to the product.
     */
    public function ingredients() :BelongsToMany
    {
        return $this->belongsToMany(Input::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function team()
    {
        return $this->belongsTo(Team::class, 'team_id');
    }

    public function targets()
    {
        return $this->hasMany(Targets::class);
    }

    public function sales()
    {
        return $this->hasMany(Sale::class, 'product_id', 'id');
    }
}
