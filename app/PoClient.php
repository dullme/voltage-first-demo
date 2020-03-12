<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class PoClient extends Model
{
    protected $fillable = [
        'project_id',
        'no',
        'client_delivery_time',
        'po_date',
    ];

    protected $dates = [
        'client_delivery_time',
        'po_date',
    ];

    //一个 客户PO# 对应多个 工厂PO#
    public function poFactories()
    {
        return $this->hasMany(PoFactory::class);
    }

    public function getClientDeliveryTimeAttribute($value)
    {
        return $value ? Carbon::parse($value)->toDateString() : null;
    }

    public function getPoDateAttribute($value)
    {
        return $value ? Carbon::parse($value)->toDateString() : null;
    }
}
