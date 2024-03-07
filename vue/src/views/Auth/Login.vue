<script setup>
import { inject } from 'vue'
import { useRoute } from 'vue-router'

const $keycloak = inject('keycloak')
const route = useRoute()

const BASE_ROUTE = import.meta.env.VITE_BASE_URL

if (!$keycloak.authenticated) {
  const redirectURL = route.query.redirect ?? '/dashboard'
  $keycloak.login({ redirectUri: BASE_ROUTE + redirectURL }).catch(err => {
    console.error(err)
    next({ name: 'not-authorized' })
  })
}
</script>
<template>
  <div class="width-wrapper">
    <p>Erfolgreich angemeldet!</p>
  </div>
</template>
