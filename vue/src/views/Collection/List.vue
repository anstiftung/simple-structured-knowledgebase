<script setup>
import { ref } from 'vue'
import CollectionService from '@/services/CollectionService'

const collections = ref([])
const collectionsMeta = ref()

const loadFromServer = page => {
  CollectionService.getCollections(page).then(({ data, meta }) => {
    collections.value = collections.value.concat(data)
    collectionsMeta.value = meta
  })
}

loadFromServer()
</script>

<template>
  <section class="bg-white">
    <!-- todo: generalize -->
    <div class="py-12 width-wrapper">
      <h2 class="mb-4 text-2xl">Alle Sammlungen</h2>
      <div class="grid grid-cols-3 gap-4">
        <router-link
          :to="{
            name: 'collection',
            params: { slug: collection.slug },
          }"
          v-for="collection in collections"
          >{{ collection.title }}</router-link
        >
      </div>
      <template
        v-if="
          collectionsMeta &&
          collectionsMeta.current_page < collectionsMeta.last_page
        "
      >
        <a
          class="block mt-8 text-blue"
          @click.prevent="loadFromServer(collectionsMeta.current_page + 1)"
          >Weitere Daten laden</a
        >
      </template>
    </div>
  </section>
</template>
