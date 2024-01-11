<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\DashboardResource;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        return new DashboardResource($user);

        // return response()->json(json_decode(Auth::token()), 200);
    }
}
