<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Modèle Agency (agence / ville).
 *
 * @property int    $id
 * @property string $name
 */
class Agency extends Model
{
    use HasFactory;

    /** @var array<int, string> */
    protected $fillable = ['name'];

    /**
     * Trajets partant de cette agence.
     *
     * @return HasMany
     */
    public function tripsFrom(): HasMany
    {
        return $this->hasMany(Trip::class, 'agency_from_id');
    }

    /**
     * Trajets arrivant à cette agence.
     *
     * @return HasMany
     */
    public function tripsTo(): HasMany
    {
        return $this->hasMany(Trip::class, 'agency_to_id');
    }
}
