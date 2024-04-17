<?php

namespace App\Http\Controllers;

use App\Models\License;
use Illuminate\Http\Request;
use App\Http\Resources\LicenseResource;
use App\Http\Controllers\BaseController;

class LicenseController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $licenses = License::orderBy('order', 'asc')->where('active', true)->get();
        return LicenseResource::collection($licenses);
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
    public function show(License $license)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, License $license)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(License $license)
    {
        //
    }
}
