<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Agency extends Model
{
    protected $fillable = ['name'];

    /** @return HasMany<Trip> */
    public function departingTrips(): HasMany
    {
        return $this->hasMany(Trip::class, 'agency_from_id');
    }

    /** @return HasMany<Trip> */
    public function arrivingTrips(): HasMany
    {
        return $this->hasMany(Trip::class, 'agency_to_id');
    }
}
