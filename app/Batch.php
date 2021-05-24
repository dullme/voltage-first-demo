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
        'project_id',
        'name',
        'sequence',
        'status',
        'carrier',
        'ocean_forwarder',
        'inland_forwarder',
        'china_inland_forwarder',
        'b_l',
        'shipping_method',
        'vessel',
        'remarks',
        'estimated_production_completion',
        'etd_port',
        'eta_port',
        'eta_job_site',
        'actual_production_completion',
        'atd_port',
        'ata_port',
        'ata_job_site',
        'apc_remarks',
        'atd_remarks',
        'ata_remarks',
        'rmb',
        'foreign_currency',
        'foreign_currency_type',
        'port_of_departure',
        'destination_port',
        'epc_history',
        'etd_port_history',
        'eta_port_history',
        'invoice_no',
        'delivery_date',
        'invoice_date',
        'shipping_ate',
        'file',
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
        'epc_history'                     => 'array',
        'etd_port_history'                => 'array',
        'eta_port_history'                => 'array',
        'created_at'                      => 'datetime:Y-m-d H:i:s',
        'updated_at'                      => 'datetime:Y-m-d',
        'estimated_production_completion' => 'datetime:Y-m-d',
        'etd_port'                        => 'datetime:Y-m-d',
        'eta_port'                        => 'datetime:Y-m-d',
        'eta_job_site'                    => 'datetime:Y-m-d',
        'actual_production_completion'    => 'datetime:Y-m-d',
        'atd_port'                        => 'datetime:Y-m-d',
        'ata_port'                        => 'datetime:Y-m-d',
        'ata_job_site'                    => 'datetime:Y-m-d',
    ];

    public function poFactory()
    {
        return $this->belongsTo(PoFactory::class);
    }

    public function containers()
    {
        return $this->hasMany(Container::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function oceanForwarder()
    {
        return $this->belongsTo(ForwarderContact::class, 'ocean_forwarder', 'id');
    }

    public function inlandForwarder()
    {
        return $this->belongsTo(ForwarderContact::class, 'inland_forwarder', 'id');
    }

    public function chinaInlandForwarder()
    {
        return $this->belongsTo(ForwarderContact::class, 'china_inland_forwarder', 'id');
    }

    public function getDeletedAtAttribute($value)
    {
        return Carbon::parse($value)->diffForHumans();
    }
}
