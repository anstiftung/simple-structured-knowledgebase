<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Http\Resources\BaseResource;
use App\Http\Resources\ArticleResource;

class CommentResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return parent::toArray($request) + [
            'type' => 'Comment',
            'id' => $this->id,
            'content' => $this->content,
            'article' => new ArticleResource($this->whenLoaded('article'))
        ];
    }
}
