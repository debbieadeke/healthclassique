<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\Title;

class SalesCallDetail extends Model
{
    use HasFactory;

    public function titles(): HasOne
    {
        return $this->hasOne(Title::class, 'id', 'title_id');
    }

	public function doublecallcolleague(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'double_call_colleague');
    }

    public function specialities(): HasOne
    {
        return $this->hasOne(Speciality::class, 'id', 'speciality_id');
    }
}
