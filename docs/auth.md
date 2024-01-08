# Authentication

CoWiki Authentication is highly customized to the needs of the "Verbund offener Werkstätten". We authorize against a [Keycloak-Server](https://www.keycloak.org/). We've installed [laravel-keycloak-guard](https://github.com/robsontenorio/laravel-keycloak-guard) for this purpose.

If you want to switch back to normal user/password authentication:

-   Remove laravel-keycloak-guard
-   prepare a migration that adds a password-column to the users-table
