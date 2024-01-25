<script setup></script>

<template>
  <div>
    <section class="bg-gray-100">
      <div class="relative width-wrapper">
        <img src="/img/bg.jpg" />
        <div
          class="absolute w-full -translate-x-1/2 md:w-6/12 top-2/3 left-1/2"
        ></div>
      </div>
    </section>
    <section class="bg-white">
      <!-- todo: generalize -->
      <div class="py-12 width-wrapper">
        <router-link :to="{ name: 'articles' }"
          >[DEBUG] Alle Beiträge [/DEBUG]</router-link
        >
        <router-link :to="{ name: 'collections' }"
          >[DEBUG] Alle Sammlungen [/DEBUG]</router-link
        >
        <h2 class="mb-4 text-2xl">Neueste Elemente</h2>
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
  </div>
</template>
