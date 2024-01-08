<?php

return [
    // The Role we search for in the keycloak-roles-array to map it to Editor-Role
    'kc_editor_role_mapping' => env('KEYCLOAK_EDITOR_ROLE', 'cowiki-admin'),
];
