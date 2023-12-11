<?php

namespace App\Models;

use App\Models\AttachedUrl;
use App\Models\AttachedFile;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasCreatedByAndUpdatedByTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Recipe extends Model
{
    use HasFactory;
    use HasCreatedByAndUpdatedByTrait;

    protected $fillable = [
        'title',
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

    public function attached_files()
    {
        return $this->morphedByMany(AttachedFile::class, 'attachment', 'recipe_attachments');
    }

    public function attached_urls()
    {
        return $this->morphedByMany(AttachedUrl::class, 'attachment', 'recipe_attachments');
    }
}
