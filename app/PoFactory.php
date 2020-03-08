<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PoFactory extends Model
{
    protected $fillable = [
        'no', 'project_id'
    ];

    public function batches()
    {
        return $this->hasMany(Batch::class);
    }
}
