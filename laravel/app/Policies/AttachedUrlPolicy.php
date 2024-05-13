<?php

namespace App\Policies;

use App\Models\AttachedUrl;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class AttachedUrlPolicy
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
    public function view(?User $user, AttachedUrl $attachedUrl): Response
    {
        if ($user?->can('list trashed attachments')) {
            return Response::allow();
        }

        return $attachedUrl->trashed()
            ? Response::denyAsNotFound()
            : Response::allow();

    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): Response
    {
        if ($user->can('add attachments')) {
            return Response::allow();
        }

        Response::deny('Du darfst keine Anhänge erstellen.');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, AttachedUrl $attachedUrl): Response
    {
        $denyResponse = Response::deny('Du darfst diesen Anhang nicht bearbeiten.');

        if ($user->can('update others attachments')) {
            return Response::allow();
        }

        if ($user->can('update own attachments')) {
            return $user->id == $attachedUrl->created_by_id
                ? Response::allow()
                : $denyResponse;
        }

        return $denyResponse;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, AttachedUrl $attachedUrl): Response
    {

        $denyResponse = Response::deny('Du darfst diesen Anhang nicht löschen.');

        if ($user->can('delete others attachments')) {
            return Response::allow();
        }

        if ($user->can('delete own attachments')) {
            return $user->id == $attachedUrl->created_by_id
                ? Response::allow()
                : $denyResponse;
        }

        return $denyResponse;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, AttachedUrl $attachedUrl): Response
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, AttachedUrl $attachedUrl): Response
    {
        if ($user->can('force delete attachments')) {
            return Response::allow();
        }

        Response::deny('Du darfst keine Anhänge endgültig löschen.');
    }
}
