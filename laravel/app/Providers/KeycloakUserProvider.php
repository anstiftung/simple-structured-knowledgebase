<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Auth\EloquentUserProvider;

class KeycloakUserProvider extends EloquentUserProvider
{
    public function retrieveOrUpdateByCredentials(\stdClass $token, array $credentials)
    {
        $user = User::where('email', $credentials['email'])->first();
        $searched_role = config('cowiki.kc_editor_role_mapping');
        $default_role = config('cowiki.kc_default_role');

        $credentials['name'] = $token->preferred_username;

        if(!$user) {
            $user = User::create($credentials);
        }

        // If the user has the kc_editor_role_mapping coming from keycloak, it becomes editor
        if(in_array($searched_role, $token->realm_access->roles) && !$user->hasRole('editor')) {
            $user->assignRole('editor');
            $user->removeRole('writer');
        } elseif (!in_array($searched_role, $token->realm_access->roles) && $user->hasRole('editor')) {
            $user->removeRole('editor');
            $user->assignRole('writer');
        }

        // If the current user has no role, it get's the default role
        if(count($user->roles->pluck('name')) < 1) {
            $user->assignRole($default_role);
        }

        return $user;
    }
}
