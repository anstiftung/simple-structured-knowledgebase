<?php

namespace App\Http\Controllers;

use App\Models\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\CollectionResource;

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

        $collections = collect($request->validate([
            '*.id' => 'required|exists:collections,id',
            '*.order' => 'required|integer',
        ]));

        $updatedCollections = collect();

        $collections->each(function ($collection, $key) use ($updatedCollections) {
            $toUpdate = Collection::find($collection['id']);
            $toUpdate->order = $collection['order'];
            $toUpdate->save();

            $updatedCollections->push($toUpdate);
        });

        return CollectionResource::collection($updatedCollections);
    }
}
