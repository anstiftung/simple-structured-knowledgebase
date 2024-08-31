# Authenticating requests

To authenticate requests, include an **`Authorization`** header with the value **`"Bearer {YOUR_AUTH_KEY}"`**.

All authenticated endpoints are marked with a `requires authentication` badge in the documentation below.

To retrive a valid token send POST-request with valid credentials to the <b>token-endpoint</b> mentioned here: https://willkommen.offene-werkstaetten.org/realms/verbund-offener-werkstaetten/.well-known/openid-configuration
