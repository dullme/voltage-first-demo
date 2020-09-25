<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Transport extends Model
{
    protected $fillable = [
        'carrier_id',
        'port_of_departure',
        'destination_port',
        'foreign_currency',
        'start_time',
        'end_time',
    ];

    protected $casts = [
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s'
    ];

    public function carriers()
    {
        return $this->belongsTo(Carrier::class, 'carrier');
    }

    public function portOfDeparture()
    {
        return $this->belongsTo(Port::class, 'port_of_departure');
    }

    public function destinationPort()
    {
        return $this->belongsTo(Port::class, 'destination_port');
    }
}
