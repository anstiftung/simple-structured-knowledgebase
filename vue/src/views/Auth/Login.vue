<script setup>
import { inject } from 'vue'
import { useUserStore } from '@/stores/user'
import { useRouter } from 'vue-router'

const $keycloak = inject('keycloak')
const userStore = useUserStore()
const router = useRouter()

const BASE_ROUTE = import.meta.env.VITE_BASE_URL

if ($keycloak.authenticated) {
  console.log('logged in. set user store now!')
  userStore.loadUserData($keycloak.token)
  router.push({ name: 'dashboard', replace: true })
} else {
  console.log('log in with keycloak')
  $keycloak
    .login({ redirectUri: BASE_ROUTE + '/auth/login/' })
    .then(auth => {
      console.log(auth)
    })
    .catch(err => {
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
