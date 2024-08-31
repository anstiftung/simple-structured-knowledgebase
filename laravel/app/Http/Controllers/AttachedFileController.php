<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\AttachedFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\BaseController;
use Illuminate\Support\Facades\Response;
use App\Http\Resources\AttachedFileResource;
use Illuminate\Validation\Rules\File as FileValidator;

/**
 * @group Attachments
 */

class AttachedFileController extends BaseController
{
    /**
     * AttachedFile Listing
     *
     * @queryParam created_by_id int only return AttachedFiles created by the defined id
     * @queryParam invalid boolean if true, return only invalid (metadata not completely filled) AttachedFiles
     */
    public function index(Request $request)
    {
        $attachedFiles = AttachedFile::when(!empty($request->creatorId), function ($query) use ($request) {
            $query->where('created_by_id', $request->creatorId);
        })
        ->when(!empty($request->invalid), function ($query) use ($request) {
            if ($request->boolean('invalid')) {
                $query->invalid();
            } else {
                $query->valid();
            }
        })
        ->orderBy('created_at', 'DESC')
        ->paginate();

        return AttachedFileResource::collection($attachedFiles);
    }

    /**
     * AttachedFile Save
     *
     * This Endpoint let's you save File-Attachments to the CoWiki.
     *
     * @urlParam article_Id int The Article id the file should be attached to.
     *
     * @authenticated
     */
    public function store(Request $request)
    {
        Gate::authorize('create', AttachedFile::class);

        $request->validate([
            'attached_files' => 'required|array|min:1',
            'attached_files.*' => [
                'required',
                FileValidator::types(config('cowiki.upload_file_types'))
                    ->max(config('cowiki.upload_file_size'))
            ],
            'article_id' => 'exists:articles,id',
        ]);

        $newAttachments = [];

        $files = $request->file('attached_files');
        foreach ($files as $file) {
            $new = AttachedFile::create([]);
            $name = $file->getClientOriginalName();
            Storage::disk('uploads')->put(
                $new->id . '/' . $name,
                file_get_contents($file->getRealPath())
            );

            $new->update([
                'filename' => $name,
                'filesize' => $file->getSize(),
                'mime_type' => $file->getMimeType(),
            ]);
            $newAttachments[] = $new;
        }

        /* If article_id is given, attach File to article */
        if ($request->input('article_id')) {
            $article = Article::find($request->input('article_id'));
            $article->attached_files()->saveMany($newAttachments);
        }

        return AttachedFileResource::collection($newAttachments);
    }

    /**
     * AttachedFile Details
     *
     * This Endpoint returns Details for the specified attached_file
     *
     * @urlParam attached_file_id int required
     * @bodyParam withArticles boolean also return all associated articles
     */
    public function show(AttachedFile $attachedFile, Request $request)
    {
        Gate::authorize('view', $attachedFile);

        if ($request->boolean('withArticles')) {
            // load only published articles for unauthenticated users
            $attachedFile->load(['articles' => function ($query) {
                if (!$this->authUser) {
                    $query->published();
                }
            }]);
        }
        return new AttachedFileResource($attachedFile);
    }


    /**
    * Serve the specified resource.
    * @hideFromAPIDocumentation
    */
    public function serve(AttachedFile $attachedFile)
    {
        $path = storage_path('uploads/' . $attachedFile->id . '/' . $attachedFile->filename);

        if (!File::exists($path)) {
            abort(404);
        }

        $file = File::get($path);
        $type = File::mimeType($path);

        $response = Response::make($file, 200);
        $response->header("Content-Type", $type)
            ->header('Content-disposition', 'inline; filename="'.$attachedFile->filename.'"');

        return $response;
    }

    /**
     * Attached File Update
     *
     * Bulk Update AttachedFile Metadata
     */
    public function update(Request $request)
    {
        $request->validate([
            'attached_files' => 'required|array|min:1',
            'attached_files.*.id' => 'required|exists:attached_files,id',
            'attached_files.*.title' => 'required|max:100',
            'attached_files.*.description' => 'required|max:250',
            'attached_files.*.source' => 'required|max:400',
            'attached_files.*.license.id' => 'required|exists:licenses,id',
        ]);

        $attachmentsToUpdate = [];

        // check for permissions and get attachedfiles
        $request->collect('attached_files')->each(function ($attachedFileFromRequest) use (&$attachmentsToUpdate) {
            $attachedFile = AttachedFile::find($attachedFileFromRequest['id']);

            Gate::authorize('update', $attachedFile);

            $attachmentsToUpdate[] = [$attachedFile, $attachedFileFromRequest];
        });

        $updatedAttachments = [];

        foreach ($attachmentsToUpdate as [$attachedFile, $attachedFileFromRequest]) {
            $attachedFile->update([
                'title' => $attachedFileFromRequest['title'],
                'description' => $attachedFileFromRequest['description'],
                'source' => $attachedFileFromRequest['source'],
                'license_id' => $attachedFileFromRequest['license']['id'],
            ]);

            if ($this->authUser->can('approve content')) {
                $attachedFile->update([
                    'approved' => $attachedFileFromRequest['approved'] ?? false
                ]);
            }
            $updatedAttachments[] = $attachedFile;
        }

        return AttachedFileResource::collection($updatedAttachments);
    }

    /**
     * AttachedFile Remove
     *
     * @bodyParam forceDelete boolean if true, AttachedFile will be deleted permanently
     */
    public function destroy(Request $request, AttachedFile $attachedFile)
    {
        $forceDelete = $request->boolean('forceDelete', false);

        if ($forceDelete === true) {
            Gate::authorize('forceDelete', $attachedFile);
            $attachedFile->forceDelete();
        } else {
            Gate::authorize('delete', $attachedFile);
            $attachedFile->delete();
        }

        return new AttachedFileResource($attachedFile);
    }
}
