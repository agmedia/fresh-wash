<?php

namespace App\Models\Back\Orders;

use App\Models\User;
use App\Models\Back\Locations\Location;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'code','user_id','location_id','reservation_id',
        'status','currency','total_net','vat_rate','total_gross','note',
    ];
    
    protected $casts = [
        'total_net' => 'decimal:2',
        'vat_rate' => 'decimal:2',
        'total_gross' => 'decimal:2',
    ];
    
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function location(): BelongsTo { return $this->belongsTo(Location::class); }
    public function payments(): HasMany { return $this->hasMany(Payment::class); }
}