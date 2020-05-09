<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FactoryContact extends Model
{
    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s'
    ];

    public function factory()
    {
        return $this->belongsTo(Factory::class);
    }
}
