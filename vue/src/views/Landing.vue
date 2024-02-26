<script setup>
import SearchForm from '@/components/SearchForm.vue'
import ExplainSection from '@/components/atoms/ExplainSection.vue'
import CollectionSection from '@/components/atoms/CollectionSection.vue'

import { ref } from 'vue'

import CollectionService from '@/services/CollectionService'

const collections = ref([])

const loadFromServer = () => {
  CollectionService.getCollections(1, { featured: true }).then(
    ({ data, meta }) => {
      collections.value = data
    },
  )
}

loadFromServer()
</script>

<template>
  <div>
    <section class="bg-gray-100">
      <div class="relative z-10 width-wrapper">
        <img src="/img/bg.jpg" />
        <div
          class="absolute w-full -translate-x-1/2 md:w-6/12 top-2/3 left-1/2"
        >
          <search-form
            class="drop-shadow-lg"
            placeholder="Suche…"
          ></search-form>
        </div>
      </div>
    </section>
    <section class="bg-white">
      <div class="py-12 width-wrapper">
        <div v-for="collection in collections" v-if="collections">
          <collection-section :collection="collection"></collection-section>
        </div>
      </div>
    </section>
    <section class="mb-24 text-center width-wrapper">
      <router-link :to="{ name: 'collections' }" class="default-button">
        Übersicht aller Sammlungen
      </router-link>
    </section>
    <section class="width-wrapper">
      <explain-section />
    </section>
  </div>
</template>
