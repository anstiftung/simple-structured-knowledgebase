<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

abstract class BaseController extends Controller
{
    use AuthorizesRequests;
    use ValidatesRequests;

    protected $authUser = null;

    public function __construct(Request $request)
    {
        $this->authUser = Auth::user();
    }

    public function abortUnauthorized($message = 'Unauthorized')
    {
        return response()->json(['message' => $message], 403);
    }

    public function abortServerError($message = 'Server Error')
    {
        return response()->json(['message' => $message], 500);

    }
}
