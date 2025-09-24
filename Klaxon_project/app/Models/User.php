<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * Attributs modifiables en masse.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',     // ex: 'admin' | 'user'
        'phone',    // numéro de téléphone (normalisé)
    ];

    /**
     * Attributs masqués en sérialisation.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casts d'attributs.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        // En Laravel 10+ tu peux ajouter : 'password' => 'hashed'
    ];

    /**
     * Accessor d'affichage lisible : "0601020304" -> "06 01 02 03 04".
     * Renvoie null si pas de téléphone.
     */
    public function getPhonePrettyAttribute(): ?string
    {
        $p = (string) ($this->phone ?? '');
        if ($p === '') {
            return null;
        }

        // Garde uniquement + et chiffres
        $normalized = preg_replace('/[^0-9\+]/', '', $p) ?? '';

        // Cas FR : +33XXXXXXXXX -> 0XXXXXXXXX
        if (str_starts_with($normalized, '+33')) {
            $normalized = '0' . substr($normalized, 3);
        }

        // On regroupe par 2 si on a bien que des chiffres
        $digits = preg_replace('/\D+/', '', $normalized) ?? '';
        if ($digits === '') {
            // Valeur non standard, on renvoie ce qu'on a
            return $normalized ?: null;
        }

        return trim(implode(' ', str_split($digits, 2)));
    }

    /**
     * Mutator : normalise le téléphone à l’enregistrement.
     * - Supprime espaces/points/tirets etc.
     * - Transforme +33XXXXXXXXX -> 0XXXXXXXXX
     */
    public function setPhoneAttribute($value): void
    {
        if ($value === null || $value === '') {
            $this->attributes['phone'] = null;
            return;
        }

        $normalized = preg_replace('/[^0-9\+]/', '', (string) $value) ?? '';

        if (str_starts_with($normalized, '+33')) {
            $normalized = '0' . substr($normalized, 3);
        }

        $this->attributes['phone'] = $normalized ?: null;
    }
}
