<script setup>
import SearchForm from '@/components/SearchForm.vue'
</script>

<template>
  <section class="bg-gray-100">
    <div class="relative width-wrapper">
      <img src="/img/bg.jpg" />
      <div class="absolute w-full -translate-x-1/2 md:w-6/12 top-2/3 left-1/2">
        <search-form />
      </div>
    </div>
  </section>
  <section class="bg-white">
    <!-- todo: generalize -->
    <div class="py-12 width-wrapper">
      <h2 class="mb-4 text-2xl font-bold">Neueste Elemente</h2>
      <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
        <div class="rounded-md aspect-square bg-blue"></div>
        <div class="rounded-md aspect-square bg-blue"></div>
        <div class="rounded-md aspect-square bg-blue"></div>
        <div class="rounded-md aspect-square bg-blue"></div>
        <div class="rounded-md aspect-square bg-blue"></div>
        <div class="rounded-md aspect-square bg-blue"></div>
        <div class="rounded-md aspect-square bg-blue"></div>
      </div>
    </div>
  </section>
</template>
