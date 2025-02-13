<script setup>
import { ref } from 'vue'
import { onClickOutside } from '@vueuse/core'
import { useUserStore } from '@/stores/user'
import { useRoute } from 'vue-router'

const route = useRoute()

const userMenuVisible = ref(false)
const userMenuOverlay = ref(null)

onClickOutside(userMenuOverlay, () => (userMenuVisible.value = false))

const userStore = useUserStore()
</script>

<template>
  <header
    class="sticky top-0 z-40 flex flex-col justify-center w-full bg-white h-header"
  >
    <div class="grid items-center grid-cols-2 gap-4 width-wrapper">
      <h1 class="text-2xl sm:text-3xl text-blue">
        <router-link to="/">
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
            <span class="hidden sm:inline">Dashboard</span>
          </router-link>
          <div
            :class="[
              'relative sm:min-w-[140px]',
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
              <span class="grow hidden sm:inline">{{ userStore.name }}</span>
              <icon name="arrow-up" v-if="userMenuVisible" />
              <icon name="arrow-down" v-else />
            </div>
            <div
              class="absolute left-[-1px] w-full user-menu-border-bottom sm:min-w-[140px] bg-white"
              v-show="userMenuVisible"
              ref="userMenuOverlay"
            >
              <!-- <button class="flex items-center gap-2 px-2 py-2">
                <img src="/icons/settings.svg" />
                Einstellungen
              </button> -->
              <router-link
                :to="{ name: 'logout' }"
                class="flex items-center gap-2 px-2 py-2"
              >
                <img src="/icons/logout.svg" />
                <span class="hidden sm:inline">Logout</span>
              </router-link>
            </div>
          </div>
        </template>
        <template v-else>
          <div class="hidden sm:flex gap-4">
            <router-link
              class="secondary-button"
              :to="{ name: 'login', query: { redirect: route.fullPath } }"
              >Login</router-link
            >
            <a
              class="default-button"
              href="https://www.offene-werkstaetten.org/de/registrieren"
              >Registrieren</a
            >
          </div>
          <div class="sm:hidden">
            <router-link
              class="secondary-button"
              :to="{ name: 'login', query: { redirect: route.fullPath } }"
              >Anmelden</router-link
            >
          </div>
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
