<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Modèle Trip (trajet inter-agences).
 *
 * @property int                             $id
 * @property int                             $agency_from_id
 * @property int                             $agency_to_id
 * @property \Illuminate\Support\Carbon|null $departure_at
 * @property \Illuminate\Support\Carbon|null $arrival_at
 * @property int                             $seats_total
 * @property int                             $seats_free
 * @property int                             $author_id
 * @property string|null                     $contact_name
 * @property string|null                     $contact_email
 * @property string|null                     $contact_phone
 *
 * @property-read \App\Models\Agency         $from
 * @property-read \App\Models\Agency         $to
 * @property-read \App\Models\User           $author
 *
 * @method static EloquentBuilder|static query()
 */
class Trip extends Model
{
    use HasFactory;

    /** @var array<int, string> */
    protected $fillable = [
        'agency_from_id',
        'agency_to_id',
        'departure_at',
        'arrival_at',
        'seats_total',
        'seats_free',
        'author_id',
        'contact_name',
        'contact_email',
        'contact_phone',
    ];

    /** @var array<string, string> */
    protected $casts = [
        'departure_at'   => 'datetime',
        'arrival_at'     => 'datetime',
        'seats_total'    => 'integer',
        'seats_free'     => 'integer',
        'agency_from_id' => 'integer',
        'agency_to_id'   => 'integer',
        'author_id'      => 'integer',
    ];

    /** Relation : agence de départ. */
    public function from(): BelongsTo
    {
        return $this->belongsTo(Agency::class, 'agency_from_id');
    }

    /** Relation : agence d’arrivée. */
    public function to(): BelongsTo
    {
        return $this->belongsTo(Agency::class, 'agency_to_id');
    }

    /** Relation : auteur (créateur du trajet). */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /**
     * Scope : trajets à venir.
     * Muter le builder puis ne rien retourner (évite les soucis d’inférence PHPStan).
     */
    public function scopeUpcoming(EloquentBuilder $q): void
    {
        $q->where('departure_at', '>', now());
    }

    /**
     * Scope : trajets avec places libres.
     */
    public function scopeWithFreeSeats(EloquentBuilder $q): void
    {
        $q->where('seats_free', '>', 0);
    }

    /**
     * Scope : tri par date de départ croissante.
     */
    public function scopeOrdered(EloquentBuilder $q): void
    {
        $q->orderBy('departure_at', 'asc');
    }
}
