<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseController;
use App\Http\Resources\UserCredentialsResource;

/**
 * @group User
 */

class UserCredentialsController extends BaseController
{
    /**
     * UserCredentials
     *
     * Sending a valid token to this Endpoint, gives you the current logged in users metadata
     *
     * @authenticated
     * @hideFromAPIDocumentation
     */

    public function index()
    {
        return new UserCredentialsResource($this->authUser);
    }
}
