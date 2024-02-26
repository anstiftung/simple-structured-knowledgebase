<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\AttachedFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\BaseController;
use Illuminate\Support\Facades\Response;
use App\Http\Resources\AttachedFileResource;
use Illuminate\Validation\Rules\File as FileValidator;

class AttachedFileController extends BaseController
{
    /**
     * Display a listing of the resource.
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
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!$this->user->can('create attached files')) {
            return parent::abortUnauthorized();
        }

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
                'mime_type' => $file->getMimeType()
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
     * Display the specified resource.
     */
    public function show(AttachedFile $attachedFile)
    {
        //@todo: this is currently unrestricted!
        return new AttachedFileResource($attachedFile);
    }


    /**
    * Serve the specified resource.
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
        $response->header("Content-Type", $type);

        return $response;
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AttachedFile $attachedFile)
    {
        if (!$this->user->can('update attached files')) {
            return parent::abortUnauthorized();
        }

        $request->validate([
            'attached_files' => 'required|array|min:1',
            'attached_files.*.id' => 'required|exists:attached_files,id',
            'attached_files.*.title' => 'required|max:100',
            'attached_files.*.description' => 'required|max:250',
            'attached_files.*.source' => 'required|max:400',
            'attached_files.*.license.id' => 'required|exists:licenses,id',
        ]);

        $updatedAttachments = [];

        $request->collect('attached_files')->each(function ($attachedFile) use (&$updatedAttachments) {
            $updated = tap(AttachedFile::find($attachedFile['id']))->update([
                'title' => $attachedFile['title'],
                'description' => $attachedFile['description'],
                'source' => $attachedFile['source'],
                'license_id' => $attachedFile['license']['id']
            ]);

            $updatedAttachments[] = $updated;
        });

        return AttachedFileResource::collection($updatedAttachments);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AttachedFile $attachedFile)
    {
        //
    }
}
