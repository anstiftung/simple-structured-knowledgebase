<?php

return [
    // The Role we search for in the keycloak-roles-array to map it to Editor-Role
    'kc_editor_role_mapping' => env('KEYCLOAK_EDITOR_ROLE', 'cowiki-admin'),
    // If the user has no role from keycloak, which can be mapped, he gets the following role
    'kc_default_role' => 'writer',

    // alowed file types for uploads: https://laravel.com/docs/10.x/validation#validating-files-file-types
    'upload_file_types' => ['png', 'jpg', 'jpeg', 'svg', 'mp4', 'zip', 'tar.gz', 'pdf', 'doc', 'xls', 'csv', 'pdf', 'ai', 'indd', 'odt', 'ods', 'odp'],
    // allowed filesite for uploads https://laravel.com/docs/10.x/validation#validating-files-file-sizes
    'upload_file_size' => '10mb',

    'system_notifcation_email' => ['redaktion@cowiki.de','post@makakken.de'],
];
