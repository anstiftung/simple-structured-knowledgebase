<script setup>
import { useUserStore } from '@/stores/user'
import { inject } from 'vue'

const $keycloak = inject('keycloak')
const userStore = useUserStore()

const BASE_ROUTE = import.meta.env.VITE_BASE_URL
const KEYCLOAK_ENABLED = import.meta.env.VITE_KEYCLOAK_ENABLED

if ($keycloak.authenticated && KEYCLOAK_ENABLED == 'true') {
  const redirectURL = '/'
  $keycloak.logout({ redirectUri: BASE_ROUTE + redirectURL })
} else {
  if (userStore.isAuthenticated) {
    userStore.deleteUserData()
  }
}
</script>
<template>
  <div class="width-wrapper">
    <p>Erfolgreich abgemeldet!</p>
  </div>
</template>
