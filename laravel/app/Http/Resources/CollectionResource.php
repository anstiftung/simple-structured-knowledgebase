<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Http\Resources\ArticleResource;
use Illuminate\Http\Resources\Json\JsonResource;

class CollectionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return parent::toArray($request) + [
            'type' => 'Collection',
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'description' => $this->description,
            'order' => $this->order,
            'featured' => $this->featured,
            'articles' => ArticleResource::collection($this->whenLoaded('articles')),
        ];
    }
}
