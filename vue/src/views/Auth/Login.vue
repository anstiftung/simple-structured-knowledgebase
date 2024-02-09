<script setup>
import { inject } from 'vue'
import { useUserStore } from '@/stores/user'
import { useRouter } from 'vue-router'

const $keycloak = inject('keycloak')
const userStore = useUserStore()
const router = useRouter()

const BASE_ROUTE = import.meta.env.VITE_BASE_URL

if ($keycloak.authenticated) {
  userStore.loadUserData($keycloak.token).then(() => {
    router.push({ name: 'dashboard', replace: true })
  })
  // router.go(-2)
} else {
  $keycloak.login({ redirectUri: BASE_ROUTE + '/auth/login/' }).catch(err => {
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
