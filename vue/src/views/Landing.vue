<script setup>
import { computed, ref } from 'vue'

import SearchForm from '@/components/search/SearchForm.vue'
import ExplainSection from '@/components/layouts/ExplainSection.vue'
import CollectionSection from '@/components/layouts/CollectionSection.vue'

import CollectionService from '@/services/CollectionService'

const collections = ref([])
const catchAllCollection = ref(null)

const loadFromServer = () => {
  CollectionService.getCollections(1, { featured: true }).then(
    ({ data, meta }) => {
      collections.value = data
    },
  )

  CollectionService.getCatchAllCollection().then(data => {
    catchAllCollection.value = data
  })
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
        <div class="divide-y-2 divide-y divide-blue-400">
          <template v-for="collection in collections" v-if="collections">
            <collection-section :collection="collection"></collection-section>
          </template>
          <template
            v-if="catchAllCollection && catchAllCollection.articles.length > 0"
          >
            <collection-section
              :collection="catchAllCollection"
            ></collection-section>
          </template>
        </div>
      </div>
    </section>
    <section class="mb-24 text-center width-wrapper" v-if="collections.length">
      <router-link :to="{ name: 'collections' }" class="default-button">
        Übersicht aller Sammlungen
      </router-link>
    </section>
    <section class="width-wrapper">
      <explain-section />
    </section>
  </div>
</template>
