<script setup>
import { inject } from 'vue'
import { useUserStore } from '@/stores/user'
import { useRouter, useRoute } from 'vue-router'

const $keycloak = inject('keycloak')
const userStore = useUserStore()
const router = useRouter()
const route = useRoute()

const BASE_ROUTE = import.meta.env.VITE_BASE_URL

if ($keycloak.authenticated) {
  const redirectURL = route.query.redirect ?? '/landing'
  $keycloak.logout({ redirectUri: BASE_ROUTE + redirectURL })
}
</script>
<template>
  <div class="width-wrapper">
    <p>Erfolgreich abgemeldet!</p>
  </div>
</template>
