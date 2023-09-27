<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

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
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'description' => $this->description,
            'recipes' => RecipeResource::collection($this->whenLoaded('recipes')),
        ];
    }
}
