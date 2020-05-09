<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ForwarderContact extends Model
{
    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s'
    ];

    public function forwarder()
    {
        return $this->belongsTo(Forwarder::class);
    }
}
