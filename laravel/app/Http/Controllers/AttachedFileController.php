<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\AttachedFile;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\File;
use App\Http\Resources\AttachedFileResource;

class AttachedFileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $attachedFiles = AttachedFile::when(!empty($request->creatorId), function ($query) use ($request) {
            $query->where('created_by_id', $request->creatorId);
        })->orderBy('updated_at', 'DESC')->paginate();

        return AttachedFileResource::collection($attachedFiles);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $request->validate([
            'attached_files' => 'required|array|min:1',
            'attached_files.*' => [
                'required',
                File::types(['png', 'jpg'])
                    ->max(12 * 1024)
            ],
            'article_id' => 'required|exists:articles,id',
        ]);

        $article = Article::find($request->input('article_id'));
        $newAttachments = [];

        $files = $request->file('attached_files');
        foreach ($files as $file) {
            $new = AttachedFile::create([]);
            $name = $file->getClientOriginalName();
            $file->storeAs(
                'public/attachedFiles/'.$new->id,
                $name
            );

            $new->update([
                'filename' => $name,
                'filesize' => $file->getSize(),
                'mime_type' => $file->getMimeType()
            ]);
            $newAttachments[] = $new;
        }

        $article->attached_files()->saveMany($newAttachments);

        return AttachedFileResource::collection($newAttachments);
    }

    /**
     * Display the specified resource.
     */
    public function show(AttachedFile $attachedFile)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, AttachedFile $attachedFile)
    {
        $request->validate([
            'attached_files' => 'required|array|min:1',
            'attached_files.*.id' => 'required|exists:attached_files,id',
            'attached_files.*.title' => 'required|max:30',
            'attached_files.*.description' => 'required|max:50',
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
