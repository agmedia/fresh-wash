<?php

namespace App\Models\Back\Pages;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PageCategory extends Model
{
    protected $fillable = ['name','slug','description','is_active'];
    
    protected $casts = ['is_active' => 'boolean'];
    
    public function pages(): HasMany
    {
        return $this->hasMany(Page::class, 'category_id');
    }
}