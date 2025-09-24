<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Trip extends Model
{
    use HasFactory;

    protected $fillable = [
        'agency_from_id',
        'agency_to_id',
        'departure_at',
        'arrival_at',
        'seats_total',
        'seats_free',
        'author_id',
    ];

    protected $casts = [
        'departure_at' => 'datetime',
        'arrival_at'   => 'datetime',
    ];

    /** @return BelongsTo<Agency, Trip> */
    public function from(): BelongsTo
    {
        return $this->belongsTo(Agency::class, 'agency_from_id');
    }

    /** @return BelongsTo<Agency, Trip> */
    public function to(): BelongsTo
    {
        return $this->belongsTo(Agency::class, 'agency_to_id');
    }

    /** @return BelongsTo<User, Trip> */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /**
     * Scope: trajets futurs uniquement.
     *
     * @param  Builder<Trip>  $q
     * @return Builder<Trip>
     */
    public function scopeUpcoming(Builder $q): Builder
    {
        return $q->where('departure_at', '>', now());
    }

    /**
     * Scope: trajets avec places libres.
     *
     * @param  Builder<Trip>  $q
     * @return Builder<Trip>
     */
    public function scopeWithFreeSeats(Builder $q): Builder
    {
        return $q->where('seats_free', '>', 0);
    }

    /**
     * Scope: ordre par date de départ croissante.
     *
     * @param  Builder<Trip>  $q
     * @return Builder<Trip>
     */
    public function scopeOrdered(Builder $q): Builder
    {
        return $q->orderBy('departure_at', 'asc');
    }
}
