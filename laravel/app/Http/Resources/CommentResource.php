<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Http\Resources\BaseResource;

class CommentResource extends BaseResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return parent::toArray($request) + [
            'type' => 'Comment',
            'content' => $this->content
        ];
    }
}
