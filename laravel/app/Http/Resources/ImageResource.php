<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Http\Resources\LicenseResource;

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
            'title' => $this->title,
            'serve_url' => '/api/attached-file/serve/' . $this->id,
            'url' => '/anhang/'.$this->id,
            'filename' => $this->filename,
            'preview_file' => $this->filename,
            'source' => $this->source,
            'license' => new LicenseResource($this->license),
        ];
    }
}
