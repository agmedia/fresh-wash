<?php

namespace App\Models\Back\Devices;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DeviceHeartbeat extends Model
{
    protected $fillable = [
        'device_id',
        'reported_at',
        'firmware_version',
        'rssi',
        'battery',
        'uptime_sec',
        'payload',
    ];
    
    protected $casts = [
        'reported_at' => 'datetime',
        'payload' => 'array',
    ];
    
    public function device(): BelongsTo
    {
        return $this->belongsTo(Device::class);
    }
}