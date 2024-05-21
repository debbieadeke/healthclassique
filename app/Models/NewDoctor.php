<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewDoctor extends Model
{

    protected $table = 'new_doctors';
    use HasFactory;

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function speciality()
    {
        return $this->belongsTo(Speciality::class);
    }
    public function facility()
    {
        return $this->belongsTo(Facility::class);
    }
    public function title()
    {
        return $this->belongsTo(Title::class);
    }

    public function getFacilitiesAttribute()
    {
        // Check if facilities_ids is not null
        if ($this->clinics !== null) {
            // Decode JSON array of facility IDs
            $facilityIds = json_decode($this->clinics);
            // Retrieve facilities with the decoded IDs
            return Facility::whereIn('id', $facilityIds)->get();
        }
        // Return null if facilities_ids is null
        return null;
    }

}
