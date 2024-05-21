<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Model;
use App\Models\Location;

class Facility extends Model
{
    use HasFactory;

    public function location(): HasOne
    {
        return $this->hasOne(Location::class, 'id', 'location_id');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->withPivot('class');
    }

    public function salescalls(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function doctors()
    {
        return $this->belongsToMany(Client::class, 'facility_doctor', 'facility_id', 'client_id');
    }
}
