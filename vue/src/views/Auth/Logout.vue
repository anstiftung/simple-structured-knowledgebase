<script setup>
import { inject } from 'vue'

const $keycloak = inject('keycloak')

const BASE_ROUTE = import.meta.env.VITE_BASE_URL

if ($keycloak.authenticated) {
  const redirectURL = '/'
  $keycloak.logout({ redirectUri: BASE_ROUTE + redirectURL })
}
</script>
<template>
  <div class="width-wrapper">
    <p>Erfolgreich abgemeldet!</p>
  </div>
</template>
