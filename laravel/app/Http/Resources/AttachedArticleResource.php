<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

class AttachedArticleResource extends BaseResource
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
            'approved' => $this->approved,
            'claps' => $this->claps,
            'description' => $this->description,
            'content' => $this->content,
            'num_attachments' => $this->attached_urls->count() + $this->attached_files->count(),
            'order' => $this->whenPivotLoaded('article_collection', function () {
                return $this->pivot->order;
            }),
        ];
    }
}
