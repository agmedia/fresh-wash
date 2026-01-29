<?php

namespace App\Models\Back\Catalog;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Service extends Model
{
    protected $fillable = [
        'code', 'name', 'description', 'sort_order', 'is_active',
    ];
    
    protected $casts = [
        'is_active' => 'bool',
        'sort_order' => 'int',
    ];
    
    public function tariffs(): HasMany
    {
        return $this->hasMany(Tariff::class);
    }
}