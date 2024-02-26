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

    protected $appends = ['isImage'];

    public function license()
    {
        return $this->belongsTo(License::class, 'license_id');
    }

    public function articles()
    {
        return $this->morphToMany(Article::class, 'article_attachments');
    }

    public function scopeValid($query)
    {
        return $query->whereNotNull('title')->whereNotNull('description')->whereNotNull('source')->whereNotNull('license_id');
    }

    public function scopeInvalid($query)
    {
        return $query->whereNull('title')->orWhereNull('description')->orWhereNull('source')->orWhereNull('license_id');
    }

    public function getIsImageAttribute($query)
    {
        return in_array($this->mime_type, ['image/png','image/jpg','image/jpeg']);
    }

    public static function boot()
    {
        parent::boot();
        self::deleting(function ($attachedFile) {
            Storage::disk('uploads')->deleteDirectory($attachedFile->id);
        });
    }
}
