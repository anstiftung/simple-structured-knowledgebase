<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

class AttachedFileResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return parent::toArray($request) + [
            'type' => 'AttachedFile',
            'id' => $this->id,
            'title' => $this->title,
            'url' => '/api/attached-file/' . $this->id,
            'description' => $this->description,
            'filename' => $this->filename,
            'mime_type' => $this->mime_type,
            'filesize' => $this->filesize,
            'preview_file' => $this->filename,
            'source' => $this->source,
            'license' => new LicenseResource($this->license),
        ];
    }
}
