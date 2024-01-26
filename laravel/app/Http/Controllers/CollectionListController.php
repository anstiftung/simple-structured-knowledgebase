<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CollectionListController extends Controller
{
    /**
     * Update a List of Resources (needed for resorting)
     */
    public function reorder(Request $request)
    {
        $user = Auth::user();
        if (!$user->can('feature collections')) {
            return parent::abortUnauthorized();
        }

        $validated = $request->validate([
            'collections.*.id' => 'required|exists:collections,id',
            'collections.*.order' => 'required|integer',
        ]);

        dd($validated);
    }
}
