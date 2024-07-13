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
            'attached_files' => AttachedFileResource::collection($this->whenLoaded('attached_files')),
            'attached_urls' => AttachedUrlResource::collection($this->whenLoaded('attached_urls')),
        ];
    }
}
