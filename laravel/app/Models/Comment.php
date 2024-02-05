<?php

namespace App\Models;

use App\Models\Article;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasCreatedByAndUpdatedByTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Comment extends Model
{
    use HasFactory;
    use HasCreatedByAndUpdatedByTrait;

    protected $fillable = [
        'content',
        'article_id'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function article()
    {
        return $this->belongsTo(Article::class, 'article_id');
    }
}
