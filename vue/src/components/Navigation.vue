<script setup>
import { inject, ref } from 'vue'
import { useRoute } from 'vue-router'
import { useUserStore } from '@/stores/user'

const userMenuVisible = ref(false)

const route = useRoute()
const userStore = useUserStore()
const $keycloak = inject('keycloak')
</script>

<template>
  <header class="sticky top-0 z-40 w-full bg-white">
    <div class="grid items-center grid-cols-2 gap-4 py-8 width-wrapper">
      <h1 class="text-3xl text-blue">
        <router-link to="/">
          <span>VOW_</span>
          <span class="font-bold">COWIKI</span>
        </router-link>
      </h1>
      <div class="flex items-center gap-4 justify-self-end">
        <template v-if="$keycloak.authenticated">
          <router-link
            :to="{ name: 'dashboard' }"
            class="flex items-center gap-2 text-gray-300"
          >
            <img src="/icons/dashboard.svg" />
            Dashboard
          </router-link>
          <div
            :class="[
              'relative text-gray-300 min-w-[140px]',
              [
                userMenuVisible
                  ? 'user-menu-border-top'
                  : 'user-menu-border-top-transparent',
              ],
            ]"
          >
            <div
              class="flex items-center gap-2 px-2 py-2 bg-white cursor-pointer"
              @click="userMenuVisible = !userMenuVisible"
            >
              <img src="/icons/user.svg" />
              <span class="grow">{{ userStore.name }}</span>
              <img
                :src="
                  userMenuVisible
                    ? '/icons/arrowUp.svg'
                    : '/icons/arrowDown.svg'
                "
              />
            </div>
            <div
              class="absolute left-[-1px] w-full user-menu-border-bottom min-w-[140px] bg-white"
              v-show="userMenuVisible"
            >
              <botton class="flex items-center gap-2 px-2 py-2">
                <img src="/icons/settings.svg" />
                Einstellungen
              </botton>
              <router-link
                tag="button"
                :to="{ name: 'logout' }"
                class="flex items-center gap-2 px-2 py-2"
              >
                <img src="/icons/logout.svg" />
                Logout
              </router-link>
            </div>
          </div>
        </template>
        <template v-else>
          <router-link class="secondary-button" :to="{ name: 'dashboard' }"
            >Login</router-link
          >
          <a
            class="default-button"
            href="https://www.offene-werkstaetten.org/de/registrieren"
            >Registrieren</a
          >
        </template>
      </div>
    </div>
  </header>
</template>

<style scoped>
.user-menu-border-top {
  @apply border border-gray-100 rounded-t-md;
}
.user-menu-border-top-transparent {
  @apply border border-transparent;
}
.user-menu-border-bottom {
  @apply border-b border-l border-r border-gray-100 rounded-b-md;
  li:first-child {
    @apply border-b border-gray-100;
  }
}
</style>
