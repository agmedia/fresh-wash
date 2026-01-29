<?php

namespace App\Models\Back\Pages;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class PageTag extends Model
{
    protected $fillable = ['name','slug'];
    
    public function pages(): BelongsToMany
    {
        return $this->belongsToMany(Page::class, 'page_page_tag', 'page_tag_id', 'page_id')
            ->withTimestamps();
    }
}