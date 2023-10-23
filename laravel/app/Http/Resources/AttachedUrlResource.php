<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

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
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'url' => $this->url,
            'preview_file' => $this->preview_file
        ];
    }
}
