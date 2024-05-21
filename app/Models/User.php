<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
//use Spatie\Permission\Test\Role;

use Spatie\Permission\Traits\HasRoles;
use App\Models\Product;
use App\Models\Territory;
use App\Models\Sale;
use Cmgmyr\Messenger\Traits\Messagable;
use Illuminate\Database\Eloquent\Builder;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles, Messagable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'active_status',
        'team_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected static function booted()
    {
        // Define a global scope to eager load the 'userBasicInfo' relationship
        static::addGlobalScope('withUserBasicInfo', function (Builder $builder) {
            $builder->with('userBasicInfo');
        });
    }

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    protected array $guard_name = ['api', 'web', 'sanctum'];

    public function activeStatus()
    {
        return $this->active_status;
    }

    public function territory():BelongsTo
    {
        return $this->belongsTo(Territory::class);
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class);
    }

    public function clients(): BelongsToMany
    {
        return $this->belongsToMany(Client::class)->withPivot('client_id', 'class', 'created_at');
    }

    public function facilities(): BelongsToMany
    {
        return $this->belongsToMany(Facility::class)->withPivot('facility_id', 'class', 'product_ids', 'created_at');
    }

	public function pharmacies(): BelongsToMany
    {
        return $this->belongsToMany(Pharmacy::class)->withPivot('pharmacy_id', 'class', 'product_ids', 'created_at');
    }

    public function sales(): BelongToMany
    {
        return $this->hasMany(Sale::class);
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function gpsRecords()
    {
        return $this->hasMany(GPSRecord::class);
    }

    public function userBasicInfo()
    {
        return $this->hasOne(UserBasicInfo::class);
    }

    public function yearlyLeaves()
    {
        return $this->hasMany(YearlyLeave::class);
    }

    public function leaveTaken()
    {
        return $this->hasMany(LeaveTaken::class);
    }

}
