<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Http\Resources\AttachedUrlResource;
use App\Http\Resources\AttachedFileResource;

class RecipeResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return parent::toArray($request) + [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'description' => $this->description,
            'attached_urls' => AttachedUrlResource::collection($this->whenLoaded('attached_urls')),
            'attached_files' => AttachedFileResource::collection($this->whenLoaded('attached_files')),
        ];
    }
}
