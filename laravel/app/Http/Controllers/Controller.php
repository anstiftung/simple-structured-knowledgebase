<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

abstract class Controller extends BaseController
{
    use AuthorizesRequests;
    use ValidatesRequests;

    public function abortUnauthorized($message = 'Unauthorized')
    {
        return response()->json(['message' => $message], 403);
    }

    public function abortServerError($message = 'Server Error')
    {
        return response()->json(['message' => $message], 500);

    }
}
