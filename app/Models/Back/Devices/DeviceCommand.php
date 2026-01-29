<?php

namespace App\Models\Back\Devices;

use App\Models\Back\Devices\Device;
use App\Models\Back\Lockers\Locker;
use App\Models\Back\Reservations\Reservation;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class DeviceCommand extends Model
{
    protected $fillable = [
        'device_id','locker_id','reservation_id','requested_by_user_id',
        'type','payload','status','sent_at','acked_at','ack_code','ack_message',
    ];

    protected $casts = [
        'payload' => 'array',
        'sent_at' => 'datetime',
        'acked_at' => 'datetime',
    ];

    public function device() { return $this->belongsTo(Device::class); }
    public function locker() { return $this->belongsTo(Locker::class); }
    public function reservation() { return $this->belongsTo(Reservation::class); }
    public function requestedBy() { return $this->belongsTo(User::class, 'requested_by_user_id'); }
}
