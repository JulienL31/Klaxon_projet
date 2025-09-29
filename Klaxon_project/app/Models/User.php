<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Modèle User (employé).
 *
 * @property int         $id
 * @property string      $name
 * @property string      $email
 * @property string|null $phone
 * @property string|null $role
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /** @var array<int, string> */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
    ];

    /** @var array<int, string> */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /** @var array<string, string> */
    protected $casts = [
        'email_verified_at' => 'datetime',
        // Laravel 10+ : 'password' => 'hashed',
    ];

    /**
     * Trajets dont l'utilisateur est l'auteur.
     *
     * @return HasMany
     */
    public function trips(): HasMany
    {
        return $this->hasMany(Trip::class, 'author_id');
    }

    /**
     * "06 01 02 03 04".
     *
     * @return string|null
     */
    public function getPhonePrettyAttribute(): ?string
    {
        $p = (string) ($this->phone ?? '');
        if ($p === '') {
            return null;
        }

        $normalized = preg_replace('/[^0-9\+]/', '', $p);

        if (str_starts_with($normalized, '+33')) {
            $normalized = '0' . substr($normalized, 3);
        }

        $digits = preg_replace('/\D+/', '', $normalized);
        if ($digits === '') {
            return $normalized;
        }

        return trim(implode(' ', str_split($digits, 2)));
    }

    /**
     * Normalise le téléphone à l’enregistrement.
     *
     * @param  string|null  $value
     * @return void
     */
    public function setPhoneAttribute(?string $value): void
    {
        if ($value === null || $value === '') {
            $this->attributes['phone'] = null;
            return;
        }

        $normalized = preg_replace('/[^0-9\+]/', '', (string) $value);

        if (str_starts_with($normalized, '+33')) {
            $normalized = '0' . substr($normalized, 3);
        }

        $this->attributes['phone'] = $normalized;
    }
}
