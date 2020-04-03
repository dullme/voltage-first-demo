<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PoFactoryHistory extends Model
{
    protected $fillable = [
        'po_factory_id',
        'po_client_id',
        'factory_id',
        'type',
        'no',
        'number',
        'remarks',
    ];
}
