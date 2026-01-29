<?php

namespace App\Models\Back\Devices;

use App\Models\Back\Locations\Location;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Device extends Model
{

    protected $fillable = [
        'location_id',
        'code',
        'name',
        'hardware_uid',
        'model',
        'firmware_version',
        'ip_address',
        'mac_address',
        'last_seen_at',
        'status',
        'is_active',
        'note',
    ];

    protected $casts = [
        'is_active'    => 'boolean',
        'last_seen_at' => 'datetime',
    ];


    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }


    public function heartbeats(): HasMany
    {
        return $this->hasMany(DeviceHeartbeat::class);
    }


    public function commands(): HasMany
    {
        return $this->hasMany(DeviceCommand::class);
    }

}