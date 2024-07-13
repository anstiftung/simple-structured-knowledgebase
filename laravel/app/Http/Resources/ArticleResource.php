<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Http\Resources\StateResource;
use App\Http\Resources\CommentResource;
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
            'title' => $this->title,
            'slug' => $this->slug,
            'state' => new StateResource($this->state),
            'approved' => $this->approved,
            'claps' => $this->claps,
            'url' => '/beitrag/' . $this->slug,
            'description' => $this->description,
            'content' => $this->content,
            'num_comments' => $this->comments ? $this->comments->count() : null,
            'num_attachments' => $this->calculateNumAttachments(),
            'attached_urls' => AttachedUrlResource::collection($this->whenLoaded('attached_urls')),
            'attached_files' => AttachedFileResource::collection($this->whenLoaded('attached_files')),
            'collections' => CollectionResource::collection($this->whenLoaded('collections')),
            'comments' => CommentResource::collection($this->whenLoaded('comments')),
            'deleted_at' => $this->deleted_at
        ];
    }

    private function calculateNumAttachments()
    {
        $num = null;
        if($this->attached_urls) {
            $num += $this->attached_urls->count();
        }
        if($this->attached_files) {
            $num += $this->attached_files->count();
        }
        return $num;
    }
}
