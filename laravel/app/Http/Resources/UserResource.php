<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'collections' => CollectionResource::collection($this->whenLoaded('collections')),
            'articles' => ArticleResource::collection($this->whenLoaded('articles')),
            'attached_files' => ArticleResource::collection($this->whenLoaded('attached_files')),
            'attached_urls' => ArticleResource::collection($this->whenLoaded('attached_urls')),
        ];
    }
}
