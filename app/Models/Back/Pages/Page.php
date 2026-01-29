<?php

namespace App\Models\Back\Pages;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Page extends Model implements HasMedia
{
    use InteractsWithMedia;
    
    protected $fillable = [
        'category_id',
        'title',
        'slug',
        'body',
        'meta_title',
        'meta_description',
        'type',
        'published_at',
        'is_active',
    ];
    
    protected $casts = [
        'published_at' => 'datetime',
        'is_active' => 'boolean',
    ];
    
    public function category(): BelongsTo
    {
        return $this->belongsTo(PageCategory::class, 'category_id');
    }
    
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(PageTag::class, 'page_page_tag', 'page_id', 'page_tag_id')
            ->withTimestamps();
    }
    
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('images');
    }
}