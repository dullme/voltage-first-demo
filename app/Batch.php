<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Batch extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'po_factory_id',
        'name',
        'sequence',
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

    public function getUpdatedAtAttribute($value)
    {
        return Carbon::parse($value)->diffForHumans();
    }

    public function getDeletedAtAttribute($value)
    {
        return Carbon::parse($value)->diffForHumans();
    }

    public function getEstimatedProductionCompletionAttribute($value)
    {
        return $value ? Carbon::parse($value)->toDateString() : null;
    }

    public function getEtdPortAttribute($value)
    {
        return $value ? Carbon::parse($value)->toDateString() : null;
    }

    public function getEtaPortAttribute($value)
    {
        return $value ? Carbon::parse($value)->toDateString() : null;
    }

    public function getEtaJobSiteAttribute($value)
    {
        return $value ? Carbon::parse($value)->toDateString() : null;
    }

    public function getActualProductionCompletionAttribute($value)
    {
        return $value ? Carbon::parse($value)->toDateString() : null;
    }

    public function getAtdPortAttribute($value)
    {
        return $value ? Carbon::parse($value)->toDateString() : null;
    }

    public function getAtaPortAttribute($value)
    {
        return $value ? Carbon::parse($value)->toDateString() : null;
    }

    public function getAtaJobSiteAttribute($value)
    {
        return $value ? Carbon::parse($value)->toDateString() : null;
    }
}
