<?php

namespace App\Providers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Contracts\Auth\Authenticatable as UserContract;

class KeycloakUserProvider extends EloquentUserProvider
{
    public function retrieveOrUpdateByCredentials(\stdClass $token, array $credentials) {
        $user = User::where('email', $credentials['email'])->first();
        $searched_role = config('cowiki.kc_editor_role_mapping');

        $credentials['name'] = $token->preferred_username;

        if(!$user) {
            $user = User::create($credentials);
        }

        // This is syncing the Role from keycloak
        if(in_array($searched_role,$token->realm_access->roles) && !$user->hasRole('editor')) {
            $user->assignRole('editor');
        } else if (!in_array($searched_role,$token->realm_access->roles) && $user->hasRole('editor')) {
            $user->removeRole('editor');
        }

        return $user;
    }
}
