<?php

namespace App\Models;

use App\Traits\HasUniqueSlugTrait;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasCreatedByAndUpdatedByTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Collection extends Model
{
    use HasFactory;
    use HasCreatedByAndUpdatedByTrait;
    use HasUniqueSlugTrait;

    protected $fillable = [
        'title',
        'description',
        'featured',
        'published',
        'created_by_id'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'featured' => 'boolean',
        'published' => 'boolean'
    ];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function scopePublished($query)
    {
        return $query->where('published', true);
    }

    public function articles()
    {
        return $this->belongsToMany(Article::class)->withPivot('order')->orderBy('order');
    }

    public function scopeFeatured($query)
    {
        return $query->where('featured', true);
    }
}
