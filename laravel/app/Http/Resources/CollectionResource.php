<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Http\Resources\AttachedArticleResource;

class CollectionResource extends BaseResource
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
            'url' => '/sammlung/' . $this->slug,
            'description' => $this->description,
            'order' => $this->order,
            'featured' => $this->featured,
            'articles' => AttachedArticleResource::collection($this->whenLoaded('articles')),
        ];
    }
}
