<script setup>
import KeyCloakService from '@/plugins/keycloak'

const callLogin = () => {
  KeyCloakService.CallLogin()
}
</script>

<template>
  <header class="sticky top-0 z-50 w-full bg-white">
    <div class="flex items-center justify-between gap-4 py-8 width-wrapper">
      <h1 class="text-3xl text-blue">
        <router-link to="/">
          <span>VOW_</span>
          <span class="font-bold">COWIKI</span>
        </router-link>
      </h1>
      <a href="#" @click.prevent="callLogin">
        <svg
          class="fill-gray-800"
          xmlns="http://www.w3.org/2000/svg"
          width="24"
          height="24"
          viewBox="0 0 24 24"
        >
          <path
            d="M12 17c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm6-9h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zM8.9 6c0-1.71 1.39-3.1 3.1-3.1s3.1 1.39 3.1 3.1v2H8.9V6zM18 20H6V10h12v10z"
          />
        </svg>
      </a>
    </div>
  </header>
</template>

<style scoped></style>
