<?php

namespace App\Models\Back\Reservations;

use App\Models\User;
use App\Models\Back\Lockers\Locker;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $fillable = [
        'user_id',
        'locker_id',
        'status',
        'starts_at',
        'ends_at',
        'price',
        'currency_code',
        'note',
    ];
    
    protected $casts = [
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'price' => 'decimal:2',
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function locker()
    {
        return $this->belongsTo(Locker::class);
    }
}