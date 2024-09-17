<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use App\Http\Controllers\BaseController;

/**
 * @group User
 */

class UserController extends BaseController
{
    /**
     * User Listing
     *
     * This Endpoint returns a list of CoWiki-users. This only works for users of role "Administrator"
     *
     * @bodyParam onlyEditors boolean Show only users with role "Editor"
     */
    public function index(Request $request)
    {
        if (!$this->authUser->can('list users')) {
            return parent::abortUnauthorized();
        }

        $users = User::orderBy('name', 'asc')
        ->when($request->boolean('onlyEditors'), function ($query) {
            $query->role('editor');
        })
        ->get();
        return UserResource::collection($users);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $user->load([
            'articles' => fn ($query) => $query->whereHas('state', function ($query) {
                $query->where('key', 'publish');
            }),
            'collections' => fn ($query) => $query->where('published', true),
            'attached_urls' => fn ($query) => $query->whereNotNull('title')->whereNotNull('description'),
            'attached_files' => fn ($query) => $query->whereNotNull('title')->whereNotNull('description')->whereNotNull('source')->whereNotNull('license_id'),
        ]);
        return new UserResource($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }
}
