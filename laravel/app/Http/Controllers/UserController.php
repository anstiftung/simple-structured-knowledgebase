<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use App\Http\Controllers\BaseController;

class UserController extends BaseController
{
    /**
     * Display User Listing from CoWiki.
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
        //
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
