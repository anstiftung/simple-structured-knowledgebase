# Authenticating requests

To authenticate requests, include an **`Authorization`** header with the value **`"Bearer {YOUR_AUTH_KEY}"`**.

All authenticated endpoints are marked with a `requires authentication` badge in the documentation below.


<p>To retrive a valid token send POST-request with valid credentials to the <b>token-endpoint</b> mentioned here: https://willkommen.offene-werkstaetten.org/realms/verbund-offener-werkstaetten/.well-known/openid-configuration</p>
<pre>Example request:
curl -X POST --url https://willkommen.offene-werkstaetten.org/realms/verbund-offener-werkstaetten/protocol/openid-connect/token \
    -H "Content-Type: application/x-www-form-urlencoded" \
    -d "client_id=api" \
    -d "username={username}" \
    -d "password={password}"
</pre>
