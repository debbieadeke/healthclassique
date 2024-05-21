<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Territory;
use App\Models\Client;

class Location extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'location_id', 'created_by'];


    public function territory(): BelongsTo
    {
        return $this->belongsTo(Territory::class);
    }

    public function clients(): HasMany
    {
        return $this->hasMany(Client::class);
    }

    public function newFacilities()
    {
        return $this->hasMany(NewFacility::class);
    }

    public function generalUploads()
    {
        return $this->hasMany(GeneralUploads::class);
    }
    public function doctors()
    {
        return $this->hasMany(NewDoctor::class);
    }
}
