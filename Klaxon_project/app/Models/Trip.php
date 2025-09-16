<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Trip extends Model
{
    protected $fillable = [
        'agency_from_id','agency_to_id','departure_dt','arrival_dt',
        'seats_total','seats_free','contact_name','contact_email','contact_phone','author_id'
    ];

    protected $casts = [
        'departure_dt' => 'datetime',
        'arrival_dt'   => 'datetime',
    ];

    public function from() { return $this->belongsTo(Agency::class, 'agency_from_id'); }
    public function to()   { return $this->belongsTo(Agency::class, 'agency_to_id'); }
}
