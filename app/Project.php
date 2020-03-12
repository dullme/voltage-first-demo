<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $dates = [
        'client_delivery_time',
        'po_date'
    ];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    //一个项目对应多个客户PO#
    public function poClients()
    {
        return $this->hasMany(PoClient::class);
    }
}
