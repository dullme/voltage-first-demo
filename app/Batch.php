<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Batch extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'po_factory_id',
        'name',
        'status',
        'carrier',
        'b_l',
        'shipping_method',
        'vessel',
        'container_no',
        'remarks',
        'estimated_production_completion',
        'etd_port',
        'eta_port',
        'eta_job_site',
        'actual_production_completion',
        'atd_port',
        'ata_port',
        'ata_job_site',
    ];

    protected $dates = [
        'estimated_production_completion',
        'etd_port',
        'eta_port',
        'eta_job_site',
        'actual_production_completion',
        'atd_port',
        'ata_port',
        'ata_job_site',
    ];

    public function poFactory()
    {
        return $this->belongsTo(PoFactory::class);
    }
}
