<script setup>
import { inject } from 'vue'
import { useUserStore } from '@/stores/user'
import { useRouter } from 'vue-router'

const $keycloak = inject('keycloak')
const userStore = useUserStore()
const router = useRouter()

if ($keycloak.authenticated) {
  $keycloak.logout({ redirectUri: 'http://localhost:8080/auth/logout' })
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
