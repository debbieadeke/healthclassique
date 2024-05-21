<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;
use App\Models\Location;
use App\Models\Speciality;
use App\Models\Title;


class Client extends Model
{
    use HasFactory;
    protected $fillable = [
        'first_name',
        'last_name',
        'code',
        'title_id',
        'speciality_id',
        'category',
        'location_id',
        'facility_code',
        'facility_type',
    ];

    public function specialities(): HasOne
    {
        return $this->hasOne(Speciality::class, 'id', 'speciality_id');
    }

    public function titles(): HasOne
    {
        return $this->hasOne(Title::class, 'id', 'title_id');
    }

    public function locations(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'location_id', 'id');
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)->withPivot('class');
    }

    public function salescalls(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'id', 'client_id');
    }

}
