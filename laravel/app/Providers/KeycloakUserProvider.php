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

        $credentials['name'] = $token->preferred_username;

        if(!$user) {
            $user = User::create($credentials);
        }

        // This is syncing the Role from keycloak
        if(in_array($searched_role, $token->realm_access->roles) && !$user->hasRole('editor')) {
            $user->assignRole('editor');
        } elseif (!in_array($searched_role, $token->realm_access->roles) && $user->hasRole('editor')) {
            $user->removeRole('editor');
        }

        return $user;
    }
}
