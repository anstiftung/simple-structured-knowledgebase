<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Http\Resources\StateResource;

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
            'title' => $this->title,
            'slug' => $this->slug,
            'state' => new StateResource($this->state),
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
