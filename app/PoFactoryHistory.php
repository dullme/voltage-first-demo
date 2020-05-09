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

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s'
    ];
}
