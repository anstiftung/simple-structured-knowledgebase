<?php

namespace App\Models;

use App\Models\Comment;
use App\Models\AttachedUrl;
use App\Models\AttachedFile;
use App\Traits\HasUniqueSlugTrait;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasCreatedByAndUpdatedByTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Article extends Model
{
    use HasFactory;
    use HasCreatedByAndUpdatedByTrait;
    use HasUniqueSlugTrait;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'content',
        'state_id'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function state()
    {
        return $this->belongsTo(State::class, 'state_id');
    }

    public function attached_files()
    {
        return $this->morphedByMany(AttachedFile::class, 'attachment', 'article_attachments');
    }

    public function attached_urls()
    {
        return $this->morphedByMany(AttachedUrl::class, 'attachment', 'article_attachments');
    }

    public function collections()
    {
        return $this->belongsToMany(Collection::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class)->orderBy('created_at', 'ASC');
    }
}
