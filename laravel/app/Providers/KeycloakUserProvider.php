<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Contracts\Auth\Authenticatable as UserContract;

class KeycloakUserProvider extends EloquentUserProvider
{
    public function retrieveOrUpdateByCredentials(\stdClass $token, array $credentials) {
        $user = User::where('email', $credentials['email'])->first();

        // dd($token);
        $credentials['name'] = $token->preferred_username;

        if(!$user) {
            $user = User::create($credentials);
        }

        return $user;
    }
}
