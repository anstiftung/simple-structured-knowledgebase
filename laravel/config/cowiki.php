<?php

return [
    // The Role we search for in the keycloak-roles-array to map it to Editor-Role
    'kc_editor_role_mapping' => env('KEYCLOAK_EDITOR_ROLE', 'cowiki-admin'),
    // If the user has no role from keycloak, which can be mapped, he gets the following role
    'kc_default_role' => 'writer',
];
