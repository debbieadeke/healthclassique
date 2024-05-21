<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;
use App\Models\Client;
use App\Models\Facility;
use App\Models\User;

class Appointment extends Model
{
    protected $fillable = [
        'start_time',
        'finish_time',
        'comments',
        'client_id',
        'user_id',
    ];

	public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function facility(): BelongsTo
    {
        return $this->belongsTo(Facility::class);
    }

    public function pharmacy(): BelongsTo
    {
        return $this->belongsTo(Pharmacy::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
