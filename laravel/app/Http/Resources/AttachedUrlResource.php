<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Http\Resources\ArticleResource;

class AttachedUrlResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return parent::toArray($request) + [
            'type' => 'AttachedUrl',
            'title' => $this->title,
            'description' => $this->description,
            'serve_url' => $this->url,
            'url' => '/url/'.$this->id,
            'preview_file' => $this->preview_file,
            'articles' => ArticleResource::collection($this->whenLoaded('articles')),
            'deleted_at' => $this->deleted_at
        ];
    }
}
