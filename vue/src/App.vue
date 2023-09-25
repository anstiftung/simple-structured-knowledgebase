<script setup>
import Navigation from './components/Navigation.vue'
import Foot from './components/Foot.vue'
</script>

<template>
  <div class="relative flex flex-col min-h-screen">
    <navigation></navigation>
    <section class="flex-1">
      <router-view></router-view>
    </section>
    <foot></foot>
  </div>
</template>

<style scoped></style>
