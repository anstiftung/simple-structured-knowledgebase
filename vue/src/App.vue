<script setup>
import Navigation from '@/components/Navigation.vue'
import Foot from '@/components/Foot.vue'
import MainModal from '@/components/MainModal.vue'
</script>

<template>
  <div class="relative flex flex-col min-h-screen">
    <main-modal />
    <Navigation></Navigation>
    <section class="flex-1">
      <router-view v-slot="{ Component }">
        <transition name="fade" mode="out-in">
          <component :is="Component" />
        </transition>
      </router-view>
    </section>
    <foot></foot>
  </div>
</template>

<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.1s ease;
}
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>
