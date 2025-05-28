<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Collection;
use App\Models\AttachedUrl;
use App\Models\AttachedFile;
use Illuminate\Http\Request;
use App\Http\Resources\ImageResource;
use App\Http\Resources\ArticleResource;
use App\Http\Controllers\BaseController;
use App\Http\Resources\CollectionResource;
use App\Http\Resources\AttachedUrlResource;
use App\Http\Resources\AttachedFileResource;

/**
 * @group Search
 */

class SearchController extends BaseController
{
    protected $query = false;
    protected $onlyPublished = true;
    protected $createdById = false;
    protected $includingTrashed = false;
    protected $sortBy = null;
    protected $sortOrder = null;

    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->query = $request->query('query', false);
        $this->onlyPublished = $request->boolean('onlyPublished');
        $this->createdById = $request->query('created_by_id', false);
        $this->includingTrashed = $request->boolean('includingTrashed', false);
        $this->sortBy = $request->query('sortBy', 'created_at');
        $this->sortOrder = $request->query('sortOrder', 'desc');
    }
    /**
     * Run Search
     *
     * @queryParam types enum The Type of content you are searching for. Possible values: articles, collections, attachments, images. For multiple values add comma-separated list.
     */
    public function search(Request $request)
    {
        $types = (array) $request->query('types', ['articles','collections','attachments','images']);

        $result = [
            'data' => [],
            'meta' => []
        ];

        $numResults = 0;

        foreach($types as $type) {
            $method = 'search' . ucfirst($type);
            if(method_exists($this, $method)) {
                extract($this->$method()); // creates $data, $meta and $count
                $result['data'] = array_merge($result['data'], $data);
                $result['meta'] = array_merge($result['meta'], $meta);

                $numResults += $count;
            }
        }

        $result['meta']['num_results'] =  $numResults;

        return response()->json($result);
    }

    public function searchImages()
    {
        $attachedImages = AttachedFile::where('title', 'like', '%' . $this->query . '%')
            ->when($this->createdById, function ($query) {
                $query->where('created_by_id', $this->createdById);
            })
            ->when($this->sortBy && $this->sortOrder, function ($query) {
                $query->orderBy($this->sortBy, $this->sortOrder);
            })
            ->get()
            ->where('isImage', true);


        $numAttachedImages = $attachedImages->count();

        return [
            'data' => [ 'images' => ImageResource::collection($attachedImages) ],
            'meta' => [ 'num_images' => $numAttachedImages ],
            'count' => $numAttachedImages
        ];
    }
    public function searchAttachments()
    {
        $attachedUrls = AttachedUrl::where('title', 'like', '%' . $this->query . '%')
            ->when($this->createdById, function ($query) {
                $query->where('created_by_id', $this->createdById);
            })
            ->when($this->includingTrashed && $this->authUser->can('list trashed attachments'), function ($query) {
                $query->withTrashed();
            })
            ->when($this->sortBy && $this->sortOrder, function ($query) {
                $query->orderBy($this->sortBy, $this->sortOrder);
            })
            ->get();

        $attachedFiles = AttachedFile::where('title', 'like', '%' . $this->query . '%')
            ->when($this->createdById, function ($query) {
                $query->where('created_by_id', $this->createdById);
            })
            ->when($this->includingTrashed && $this->authUser->can('list trashed attachments'), function ($query) {
                $query->withTrashed();
            })
            ->when($this->sortBy && $this->sortOrder, function ($query) {
                $query->orderBy($this->sortBy, $this->sortOrder);
            })
            ->get();
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
            ],
            'count' => $numAttachedFiles + $numAttachedUrls

        ];
    }

    public function searchCollections()
    {
        $collections = Collection::where('title', 'like', '%' . $this->query . '%')
            ->when($this->createdById, function ($query) {
                $query->where('created_by_id', $this->createdById);
            })
            ->when(empty($this->authUser) || $this->onlyPublished, function ($query) {
                $query->published();
            })
            ->when($this->sortBy && $this->sortOrder, function ($query) {
                $query->orderBy($this->sortBy, $this->sortOrder);
            })
            ->get();
        $numCollections = $collections->count();

        return [
            'data' => [ 'collections' => CollectionResource::collection($collections) ],
            'meta' => [ 'num_collections' => $numCollections ],
            'count' => $numCollections
        ];
    }
    public function searchArticles()
    {
        $query = Article::where('title', 'like', '%' . $this->query . '%')
            ->when($this->createdById, function ($query) {
                $query->where('created_by_id', $this->createdById);
            })
            ->when($this->includingTrashed && $this->authUser->can('list trashed articles'), function ($query) {
                $query->withTrashed();
            })
            ->when(empty($this->authUser) || $this->onlyPublished, function ($query) {
                $query->published();
            });

        if($this->authUser) {
            $articlesOwn = Article::where('created_by_id', $this->authUser->id);
            $query = $query->union($articlesOwn);
        }

        $query->when($this->sortBy && $this->sortOrder, function ($query) {
            $query->orderBy($this->sortBy, $this->sortOrder);
        });

        $articles = $query->get();

        $numArticles = $articles->count();

        return [
            'data' => [ 'articles' => ArticleResource::collection($articles) ],
            'meta' => [ 'num_articles' => $numArticles ],
            'count' => $numArticles
        ];
    }
}
