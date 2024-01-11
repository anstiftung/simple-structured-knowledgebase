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
      <router-view></router-view>
    </section>
    <foot></foot>
  </div>
</template>

<style scoped></style>
