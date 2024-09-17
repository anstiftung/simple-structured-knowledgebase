<?php

namespace App\Http\Controllers;

use App\Models\Collection;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use App\Http\Resources\CollectionResource;

/**
 * @group Collections
 */

class CollectionListController extends BaseController
{
    /**
     * Collections Reorder
     *
     * You can use this endpoint to resort Collections. Send a JSON list of collection id's with corresponding sort-order values.
     * Example: `[ {"id":2,"order":0}, {"id":5,"order":2}, {"id":7,"order":1} ]`
     *
     * @authenticated
     */
    public function reorder(Request $request)
    {
        if (!$this->authUser->can('feature collections')) {
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
