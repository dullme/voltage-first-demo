<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $dates = [
        'client_delivery_time',
        'po_date'
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function poFactory()
    {
        return $this->hasMany(PoFactory::class);
    }
}
