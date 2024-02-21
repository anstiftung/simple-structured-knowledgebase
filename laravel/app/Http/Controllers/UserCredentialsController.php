<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseController;
use App\Http\Resources\UserCredentialsResource;

class UserCredentialsController extends BaseController
{
    public function index()
    {
        return new UserCredentialsResource($this->user);
    }
}
