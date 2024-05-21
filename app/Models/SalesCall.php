<?php

namespace App\Models;

use App\Models\SalesCallDetail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;
use App\Models\Facility;
use App\Models\Speciality;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

use App\Models\User;


class SalesCall extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

    public function facility(): HasOne
    {
        return $this->hasOne(Facility::class, 'id', 'client_id');
    }

    public function speciality(): HasOne
    {
        return $this->hasOne(Speciality::class, 'id', 'speciality_id');
    }

    public function client(): HasOne
    {
        return $this->hasOne(Client::class, 'id', 'client_id');
    }

    public function pharmacy(): HasOne
    {
        return $this->hasOne(Pharmacy::class, 'id', 'client_id');
    }

    public function salescalldetails(): HasMany
    {
        return $this->hasMany(SalesCallDetail::class);

    }

    public function sampleSlip(): HasMany
    {
        return $this->hasMany(SampleSlip::class);

    }

    public function productSample(): HasMany
    {
        return $this->hasMany(ProductSample::class, 'salescall_or_detail_id');

    }

    public function doublecallcolleague(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'double_call_colleague');
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('pharmacy_audit')
            ->singleFile(); // Use single file for profile picture
    }

    public function salesperson(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'created_by');
    }

    /*
    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(500)
            ->height(500)
            ->sharpen(10)
            ->quality(70); // Set the compression quality
    }
    */
}
