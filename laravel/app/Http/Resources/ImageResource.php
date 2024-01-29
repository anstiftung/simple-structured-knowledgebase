<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

class ImageResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return parent::toArray($request) + [
            'type' => 'Image',
            'id' => $this->id,
            'url' => '/api/attached-file/' . $this->id,
            'filename' => $this->filename,
            'preview_file' => $this->filename,
            'source' => $this->source,
            'license' => new LicenseResource($this->license),
        ];
    }
}
