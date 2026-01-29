<?php
namespace App\Models\Back\Lockers;

use App\Models\Back\Locations\Location;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Model;

class Locker extends Model
{
    use Sluggable;
    
    protected $fillable = [
        'location_id',
        'code',
        'name',
        'slug',
        'size',
        'is_active',
        'is_available',
        'hardware_uid',
        'note',
    ];
    
    protected $casts = [
        'is_active' => 'boolean',
        'is_available' => 'boolean',
    ];
    
    public function location()
    {
        return $this->belongsTo(Location::class);
    }
    
    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => fn () => $this->code . '-' . ($this->name ?? ''),
            ],
        ];
    }
}