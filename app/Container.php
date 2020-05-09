<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Container extends Model
{
    protected $fillable = [
        'batch_id',
        'no',
        'type',
        'remarks',
        'eta_job_site',
        'ata_job_site',
    ];


    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s'
    ];

    protected $dates = [
        'eta_job_site',
        'ata_job_site',
    ];

    public function getEtaJobSiteAttribute($value)
    {
        return $value ? Carbon::parse($value)->toDateString() : null;
    }

    public function getAtaJobSiteAttribute($value)
    {
        return $value ? Carbon::parse($value)->toDateString() : null;
    }
}