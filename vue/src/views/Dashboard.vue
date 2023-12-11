<script setup>
import { ref } from 'vue'
import axios from 'axios'

const username = ref();
const email = ref();
const statusCode = ref(404);

// ToDo: Implement API-Call to keycloak-guard secured route.
axios.get('api/dashboard', {
    headers: {
        'Authorization': 'Bearer ' + window.localStorage.getItem('keycloakToken'),
    }
}).then((response) => {
    console.log(response)
    username.value = response.data.preferred_username
    email.value = response.data.email
    statusCode.value = response.status
})

</script>
<template>
    <section class="bg-white">
        <!-- todo: generalize -->
        <div class="py-12 width-wrapper">
            <p class="mb-2">Dieses Profil ist nur sichtbar wenn die Keycloak-Auth erfolgreich war.</p>

            <div v-if="statusCode == 200">
                <p><strong>Benutzername:</strong> {{username}}</p>
                <p><strong>E-Mail Adresse:</strong> {{email}}</p>
            </div>
            <div v-else>
                <p><strong>Fehler:</strong> Du bist nicht authorisiert diese Inhalte zu sehen.</p>
            </div>
        </div>
    </section>
</template>
