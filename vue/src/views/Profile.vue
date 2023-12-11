<script setup>
import axios from 'axios'
// ToDo: Implement API-Call to keycloak-guard secured route.
axios.get('api/protected-endpoint', {
    headers: {
        'Authorization': 'Bearer ' + window.localStorage.getItem('keycloakToken'),
    }
}).then((response) => {
    console.log(response)
})

</script>
<template>
    <section class="bg-white">
        <!-- todo: generalize -->
        <div class="py-12 width-wrapper">
            <p>Dieses Profil ist nur sichtbar wenn die Keycloak-Auth erfolgreich war.</p>
        </div>
    </section>
</template>
