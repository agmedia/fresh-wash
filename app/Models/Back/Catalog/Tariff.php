<?php

namespace App\Models\Back\Catalog;

use App\Models\Back\Locations\Location;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Tariff extends Model
{
    protected $fillable = [
        'service_id',
        'location_id',
        'price_net',
        'vat_rate',
        'price_gross',
        'currency',
        'is_active',
        'valid_from',
        'valid_to',
    ];
    
    protected $casts = [
        'price_net' => 'decimal:2',
        'vat_rate' => 'decimal:2',
        'price_gross' => 'decimal:2',
        'is_active' => 'bool',
        'valid_from' => 'date',
        'valid_to' => 'date',
    ];
    
    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }
    
    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }
}