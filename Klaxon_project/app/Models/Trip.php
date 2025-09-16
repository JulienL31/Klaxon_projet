<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Trip extends Model
{
    protected $fillable = [
        'agency_from_id',
        'agency_to_id',
        'departure_dt',
        'arrival_dt',
        'seats_total',
        'seats_free',
        'contact_name',
        'contact_email',
        'contact_phone',
        'author_id',
    ];

    protected $casts = [
        'departure_dt' => 'datetime',
        'arrival_dt'   => 'datetime',
    ];

    /* Relations */
    public function from(): BelongsTo
    {
        return $this->belongsTo(Agency::class, 'agency_from_id');
    }

    public function to(): BelongsTo
    {
        return $this->belongsTo(Agency::class, 'agency_to_id');
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /* Scopes utilitaires */
    public function scopeUpcoming($q)
    {
        return $q->where('departure_dt', '>', now());
    }

    public function scopeWithFreeSeats($q)
    {
        return $q->where('seats_free', '>', 0);
    }

    public function scopeOrdered($q)
    {
        return $q->orderBy('departure_dt', 'asc');
    }
}
