<?php

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
        'departure_at' => 'datetime',
        'arrival_at'   => 'datetime',
    ];

    /**
     * Agence de départ.
     *
     * @return BelongsTo
     */
    public function from(): BelongsTo
    {
        return $this->belongsTo(Agency::class, 'agency_from_id');
    }

    /**
     * Agence d'arrivée.
     *
     * @return BelongsTo
     */
    public function to(): BelongsTo
    {
        return $this->belongsTo(Agency::class, 'agency_to_id');
    }

    /**
     * Auteur du trajet.
     *
     * @return BelongsTo
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /**
     * Scope : trajets futurs uniquement.
     *
     * @param  EloquentBuilder  $q
     * @return EloquentBuilder
     */
    public function scopeUpcoming(EloquentBuilder $q): EloquentBuilder
    {
        $q->where('departure_at', '>', now());
        return $q;
    }

    /**
     * Scope : trajets avec places libres.
     *
     * @param  EloquentBuilder  $q
     * @return EloquentBuilder
     */
    public function scopeWithFreeSeats(EloquentBuilder $q): EloquentBuilder
    {
        $q->where('seats_free', '>', 0);
        return $q;
    }

    /**
     * Scope : tri par date de départ croissante.
     *
     * @param  EloquentBuilder  $q
     * @return EloquentBuilder
     */
    public function scopeOrdered(EloquentBuilder $q): EloquentBuilder
    {
        $q->orderBy('departure_at', 'asc');
        return $q;
    }
}
