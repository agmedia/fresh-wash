<?php

namespace App\Models\Back\Locations;

use App\Models\Back\Devices\Device;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Location extends Model
{

    use Sluggable;

    protected $fillable = [
        'name',
        'slug',
        'address_line1',
        'address_line2',
        'postal_code',
        'city',
        'country_code',
        'lat',
        'lng',
        'is_active',
        'note',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'lat'       => 'decimal:7',
        'lng'       => 'decimal:7',
    ];


    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name',
            ],
        ];
    }


    public function device(): HasOne
    {
        return $this->hasOne(Device::class);
    }
}