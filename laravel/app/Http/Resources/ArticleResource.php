<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Http\Resources\CollectionResource;
use App\Http\Resources\AttachedUrlResource;
use App\Http\Resources\AttachedFileResource;

class ArticleResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return parent::toArray($request) + [
            'type' => 'Article',
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'url' => '/beitrag/' . $this->slug,
            'description' => $this->description,
            'content' => $this->content,
            'num_attachments' => $this->attached_urls->count() + $this->attached_files->count(),
            'attached_urls' => AttachedUrlResource::collection($this->whenLoaded('attached_urls')),
            'attached_files' => AttachedFileResource::collection($this->whenLoaded('attached_files')),
            'collections' => CollectionResource::collection($this->whenLoaded('collections'))
        ];
    }
}