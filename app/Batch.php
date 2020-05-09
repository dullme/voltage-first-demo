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
        'ocean_forwarder',
        'inland_forwarder',
        'b_l',
        'shipping_method',
        'vessel',
        'remarks',
        'estimated_production_completion',
        'etd_port',
        'eta_port',
        'actual_production_completion',
        'atd_port',
        'ata_port',
        'rmb',
        'foreign_currency',
        'foreign_currency_type',
        'port_of_departure',
        'destination_port',
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

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s'
    ];

    public function poFactory()
    {
        return $this->belongsTo(PoFactory::class);
    }

    public function containers()
    {
        return $this->hasMany(Container::class);
    }

    public function getUpdatedAtAttribute($value)
    {
        return Carbon::parse($value)->toDateString();
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
}
