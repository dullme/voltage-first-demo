<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Project extends Model
{
    protected $fillable = [
        'client_id',
        'name',
        'number',
        'contacts'
    ];

    protected $dates = [
        'client_delivery_time',
        'po_date'
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s'
    ];

    public function getContactsAttribute($value)
    {
        return explode(',', $value);
    }

    public function setContactsAttribute($value)
    {
        $this->attributes['contacts'] = implode(',', $value);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    //一个项目对应多个客户PO#
    public function poClients()
    {
        return $this->hasMany(PoClient::class);
    }

    public function author()
    {
        return $this->belongsTo(AdminUser::class);
    }
}
