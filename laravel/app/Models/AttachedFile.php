<?php

namespace App\Models;

use App\Traits\HasCreatedByAndUpdatedByTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class AttachedFile extends Model
{
    use HasFactory;
    use HasCreatedByAndUpdatedByTrait;

    protected $fillable = [
        'title',
        'description',
        'filename',
        'filesize',
        'mime_type',
        'preview_file',
        'source',
        'license_id'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function license()
    {
        return $this->belongsTo(License::class, 'license_id');
    }

    public function articles()
    {
        return $this->morphToMany(Article::class, 'article_attachments');
    }

    public static function boot()
    {
        parent::boot();
        self::deleting(function ($attachedFile) {
            Storage::deleteDirectory('public/attachedFiles/'.$attachedFile->id);
        });
    }
}
