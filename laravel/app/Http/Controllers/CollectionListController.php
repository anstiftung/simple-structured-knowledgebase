<?php

namespace App\Http\Controllers;

use App\Models\Collection;
use Illuminate\Http\Request;
use App\Http\Controllers\BaseController;
use App\Http\Resources\CollectionResource;

class CollectionListController extends BaseController
{
    /**
     * Update a List of Resources (needed for resorting)
     */
    public function reorder(Request $request)
    {
        if (!$this->user->can('feature collections')) {
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
