<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PoFactoryFactory extends Model
{
    protected $fillable = [
        'po_factory_id',
        'factory_id',
        'no',
        'remarks',
        'product',
    ];

    protected $casts = [
        'created_at'                      => 'datetime:Y-m-d H:i:s',
        'updated_at'                      => 'datetime:Y-m-d H:i:s',
    ];

    public function factory()
    {
        return $this->belongsTo(Factory::class);
    }
}
