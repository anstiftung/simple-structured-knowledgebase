<script setup>
import { inject, ref } from 'vue'
import { onClickOutside } from '@vueuse/core'
import { useUserStore } from '@/stores/user'

const userMenuVisible = ref(false)
const userMenuOverlay = ref(null)

onClickOutside(userMenuOverlay, () => (userMenuVisible.value = false))

const userStore = useUserStore()
const $keycloak = inject('keycloak')
</script>

<template>
  <header
    class="sticky top-0 z-40 flex flex-col justify-center w-full bg-white h-header"
  >
    <div class="grid items-center grid-cols-2 gap-4 width-wrapper">
      <h1 class="text-3xl text-blue">
        <router-link to="/">
          <span>VOW_</span>
          <span class="font-bold">COWIKI</span>
        </router-link>
      </h1>
      <div class="flex items-center gap-4 text-gray-300 justify-self-end">
        <template v-if="userStore.isAuthenticated">
          <router-link
            :to="{ name: 'dashboard' }"
            class="flex items-center gap-2"
            active-class="text-black fill-black"
          >
            <icon name="dashboard" />
            Dashboard
          </router-link>
          <div
            :class="[
              'relative min-w-[140px]',
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
              <icon name="user" />
              <span class="grow">{{ userStore.name }}</span>
              <icon name="arrow-up" v-if="userMenuVisible" />
              <icon name="arrow-down" v-else />
            </div>
            <div
              class="absolute left-[-1px] w-full user-menu-border-bottom min-w-[140px] bg-white"
              v-show="userMenuVisible"
              ref="userMenuOverlay"
            >
              <button class="flex items-center gap-2 px-2 py-2">
                <img src="/icons/settings.svg" />
                Einstellungen
              </button>
              <router-link
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
          <router-link class="secondary-button" :to="{ name: 'login' }"
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
