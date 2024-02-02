<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Collection;
use App\Models\AttachedUrl;
use App\Models\AttachedFile;
use Illuminate\Http\Request;
use App\Http\Resources\ImageResource;
use App\Http\Resources\ArticleResource;
use App\Http\Resources\CollectionResource;
use App\Http\Resources\AttachedUrlResource;
use App\Http\Resources\AttachedFileResource;

class SearchController extends Controller
{
    protected $query = false;

    public function __construct(Request $request)
    {
        $this->query = $request->query('query', false);
    }
    /**
     * Run Search
     */
    public function search(Request $request)
    {
        $types = $request->query('types', ['articles','collections','attachments','images']);

        $result = [
            'data' => [],
            'meta' => []
        ];

        foreach($types as $type) {
            $method = 'search' . ucfirst($type);
            if(method_exists($this, $method)) {
                $result['data'] = array_merge($result['data'], $this->$method['data']);
                $result['meta'] = array_merge($result['meta'], $this->$method['meta']);
            }
            // I think we could refactor meta -> RecourceCollectionClass add count
        }

        return response()->json($result);
    }

    public function searchImages()
    {
        $attachedImages = AttachedFile::where('title', 'like', '%' . $this->query . '%')
            ->whereIn('mime_type', ['image/png','image/jpg','image/jpeg'])
            ->orderBy('created_at', 'DESC')->get();

        $numAttachedImages = $attachedImages->count();

        return [
            'data' => [ 'images' => ImageResource::collection($attachedImages) ],
            'meta' => [ 'num_images' => $numAttachedImages ]
        ];
    }
    public function searchAttachments()
    {
        $attachedUrls = AttachedUrl::where('title', 'like', '%' . $this->query . '%')->get();
        $attachedFiles = AttachedFile::where('title', 'like', '%' . $this->query . '%')->get();
        $numAttachedUrls = $attachedUrls->count();
        $numAttachedFiles = $attachedFiles->count();

        return [
            'data' => [
                'attached_urls' => AttachedUrlResource::collection($attachedUrls),
                'attached_files' => AttachedFileResource::collection($attachedFiles)
            ],
            'meta' => [
                'num_attached_urls' => $numAttachedUrls,
                'num_attached_files' => $numAttachedFiles,
            ]
        ];
    }

    public function searchCollections()
    {
        $collections = Collection::where('title', 'like', '%' . $this->query . '%')->orderBy('created_at', 'DESC')->get();
        $numCollections = $collections->count();

        return [
            'data' => [ 'collections' => CollectionResource::collection($collections) ],
            'meta' => [ 'num_collections' => $numCollections ]
        ];
    }
    public function searchArticles()
    {
        $articles = Article::where('title', 'like', '%' . $this->query . '%')->get();
        $numArticles = $articles->count();

        return [
            'data' => [ 'articles' => ArticleResource::collection($articles) ],
            'meta' => [ 'num_articles' => $numArticles ]
        ];
    }
}
