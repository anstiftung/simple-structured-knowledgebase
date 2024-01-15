<script setup>
import { useRoute } from 'vue-router'
import { useUserStore } from '@/stores/user'

const route = useRoute()
const userStore = useUserStore()
</script>

<template>
  <header class="sticky top-0 z-40 w-full bg-white">
    <div class="grid items-center grid-cols-3 gap-4 py-8 width-wrapper">
      <h1 class="text-3xl text-blue">
        <router-link to="/">
          <span>VOW_</span>
          <span class="font-bold">COWIKI</span>
        </router-link>
      </h1>
      <h2 class="justify-self-center">{{ route.meta.navTitle }}</h2>
      <div class="flex items-center gap-2 justify-self-end">
        <h3 v-if="userStore.id">
          {{ userStore.name }}
        </h3>
        <router-link :to="{ name: 'dashboard' }">
          <svg
            v-if="userStore.id"
            xmlns="http://www.w3.org/2000/svg"
            width="24"
            height="24"
            fill="#000000"
            viewBox="0 0 256 256"
          >
            <path
              d="M208,80H96V56a32,32,0,0,1,32-32c15.37,0,29.2,11,32.16,25.59a8,8,0,0,0,15.68-3.18C171.32,24.15,151.2,8,128,8A48.05,48.05,0,0,0,80,56V80H48A16,16,0,0,0,32,96V208a16,16,0,0,0,16,16H208a16,16,0,0,0,16-16V96A16,16,0,0,0,208,80Zm0,128H48V96H208V208Z"
            ></path>
          </svg>
          <svg
            class="fill-gray-800"
            v-else
            xmlns="http://www.w3.org/2000/svg"
            width="24"
            height="24"
            viewBox="0 0 24 24"
          >
            <path
              d="M12 17c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm6-9h-1V6c0-2.76-2.24-5-5-5S7 3.24 7 6v2H6c-1.1 0-2 .9-2 2v10c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V10c0-1.1-.9-2-2-2zM8.9 6c0-1.71 1.39-3.1 3.1-3.1s3.1 1.39 3.1 3.1v2H8.9V6zM18 20H6V10h12v10z"
            />
          </svg>
        </router-link>
      </div>
    </div>
  </header>
</template>

<style scoped></style>
