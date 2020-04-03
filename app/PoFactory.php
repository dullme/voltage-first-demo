<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PoFactory extends Model
{
    protected $fillable = [
        'no',
        'number',
        'po_client_id',
        'factory_id',
        'type',
        'remarks',
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s'
    ];

    public function batches()
    {
        return $this->hasMany(Batch::class);
    }

    public function factory()
    {
        return $this->belongsTo(Factory::class);
    }

    public function poClient()
    {
        return $this->belongsTo(PoClient::class);
    }

    public function poFactoryHistories()
    {
        return $this->hasMany(PoFactoryHistory::class);
    }
}
