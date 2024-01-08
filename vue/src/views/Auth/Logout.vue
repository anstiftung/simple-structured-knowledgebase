<script setup>
import { inject } from 'vue'

const $keycloak = inject('keycloak')

if($keycloak.authenticated) {
    $keycloak.logout().then(() => {
        console.log('logout successfull')
    }).catch(err => {
        console.error(err)
    })
}
</script>
<template>
    <p>Erfolgreich abgemeldet!</p>
</template>
