<?php

namespace App\Models\Back\Orders;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $fillable = [
        'order_id','provider','status','amount','currency','provider_ref','paid_at','payload',
    ];
    
    protected $casts = [
        'amount' => 'decimal:2',
        'paid_at' => 'datetime',
        'payload' => 'array',
    ];
    
    public function order(): BelongsTo { return $this->belongsTo(Order::class); }
}