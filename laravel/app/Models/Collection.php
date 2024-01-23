<?php

namespace App\Models;

use App\Traits\HasUniqueSlugTrait;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasCreatedByAndUpdatedByTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Collection extends Model
{
    use HasFactory;
    use HasCreatedByAndUpdatedByTrait;
    use HasUniqueSlugTrait;

    protected $fillable = [
        'title',
        'slug',
        'description'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function articles(): BelongsToMany
    {
        return $this->belongsToMany(Article::class);
    }

    public function scopeFeatured($query)
    {
        return $query->where('featured',true);
    }
}
