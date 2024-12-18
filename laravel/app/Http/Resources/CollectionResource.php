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
            'title' => $this->title,
            'slug' => $this->slug,
            'url' => '/sammlung/' . $this->slug,
            'description' => $this->description,
            'published' => $this->published,
            'order' => $this->order,
            'featured' => $this->featured,
            'num_articles' => $this->articles->count(),
            'articles' => AttachedArticleResource::collection($this->whenLoaded('articles')),
        ];
    }
}
