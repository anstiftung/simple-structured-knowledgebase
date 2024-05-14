<?php

namespace App\Policies;

use App\Models\Collection;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CollectionPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): Response
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(?User $user, Collection $collection): Response
    {
        if ($collection->published) {
            return Response::allow();
        }
        // logged in users are allowed to view unpublished collections
        if ($user !== null) {
            return Response::allow();
        }

        return Response::denyAsNotFound();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): Response
    {
        if ($user->can('add collections')) {
            return Response::allow();
        }

        Response::deny('Du darfst keine Sammlungen erstellen.');

    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Collection $collection): Response
    {
        if ($user->can('update collections')) {
            return Response::allow();
        }

        return Response::deny('Du darfst diese Sammlung nicht bearbeiten.');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Collection $collection): Response
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Collection $collection): Response
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Collection $collection): Response
    {
        //
    }
}
