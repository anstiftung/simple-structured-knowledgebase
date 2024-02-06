<?php

namespace App\Models;

use App\Models\Article;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasCreatedByAndUpdatedByTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AttachedUrl extends Model
{
    use HasFactory;
    use HasCreatedByAndUpdatedByTrait;

    protected $fillable = [
        'title',
        'description',
        'url',
        'preview_file',
        'crawled_at',
        'crawled_status',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function articles()
    {
        return $this->morphToMany(Article::class, 'article_attachments');
    }

    public function scopeValid($query)
    {
        return $query->whereNotNull('title')->whereNotNull('description');
    }

    public function scopeInvalid($query)
    {
        return $query->whereNull('title')->orWhereNull('description');
    }
}