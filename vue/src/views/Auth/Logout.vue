<script setup>
import { inject } from 'vue'
import { useUserStore } from '@/stores/user'
import { useRouter } from 'vue-router'

const $keycloak = inject('keycloak')
const userStore = useUserStore()
const router = useRouter()

const BASE_ROUTE = import.meta.env.VITE_BASE_URL

if ($keycloak.authenticated) {
  $keycloak.logout({ redirectUri: BASE_ROUTE + 'auth/logout' })
} else {
  userStore.logout()
  router.push({ name: 'landing', replace: true })
}
</script>
<template>
  <div class="width-wrapper">
    <p>Erfolgreich abgemeldet!</p>
  </div>
</template>
