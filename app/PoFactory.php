<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PoFactory extends Model
{
    protected $fillable = [
        'no', 'po_client_id'
    ];

    public function batches()
    {
        return $this->hasMany(Batch::class);
    }
}
