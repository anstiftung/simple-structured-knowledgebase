<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

class IngredientResource extends BaseResource
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
            'filename' => $this->filename,
            'source' => $this->source,
            'license' => new LicenseResource($this->license),
        ];
    }
}
