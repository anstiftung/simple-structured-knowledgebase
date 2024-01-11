<script setup>
import { ref } from 'vue'

const loadFromServer = () => {}

loadFromServer()
</script>

<template>
  <section class="bg-orange/50">
    <div class="px-12 py-8 header-clip rounded-[20px] bg-orange">
      <div class="text-center width-wrapper">Neuer Artikel</div>
    </div>
    <div class="grid grid-cols-6 width-wrapper min-h-[70vh]">
      <div class="col-span-4 px-8 py-16 bg-white">@todo: Editor!</div>
      <div
        class="flex flex-col justify-between col-span-2 px-8 py-16 bg-gray-100"
      >
        <div class="text-sm">@todo: Edit creator and state of the article!</div>
        <div class="flex justify-end gap-4">
          <button class="secondary-button">Verwerfen</button>
          <button class="default-button">Speichern</button>
        </div>
      </div>
    </div>
  </section>
</template>

<style scoped>
.header-clip {
  /* clip-path: polygon(0px 0px, 0 80%, 100% 100%, 100% 0px); */
}
</style>
