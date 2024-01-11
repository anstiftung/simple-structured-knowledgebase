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
    <div class="flex width-wrapper min-h-[70vh]">
      <div class="px-8 py-16 bg-white grow">
        Lorem ipsum dolor sit amet consectetur adipisicing elit. Accusamus aut
        odit qui, reiciendis tempora sunt numquam! Quae rerum eveniet quo labore
        dignissimos. Quaerat, dolorum vel excepturi molestias nostrum quo illum.
      </div>
      <div
        class="bg-gray-100 basis-[800px] px-8 py-16 flex flex-col justify-between"
      >
        <div class="text-sm">
          Lorem ipsum, dolor sit amet consectetur adipisicing elit. Delectus
          neque quia consequuntur aut repellat inventore ex. Fugiat quos at
          impedit maxime autem illo quo esse, dolore optio repellendus quod
          error?
        </div>
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
