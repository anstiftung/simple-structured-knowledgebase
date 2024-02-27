<script setup>
import { ref } from 'vue'

import CollectionService from '@/services/CollectionService'

import ModelHeader from '@/components/layouts/ModelHeader.vue'
import Separator from '@/components/layouts/Separator.vue'
import CollectionSection from '@/components/atoms/CollectionSection.vue'
import LoadingSpinner from '@/components/atoms/LoadingSpinner.vue'

const collections = ref([])
const collectionsMeta = ref(null)
const isLoading = ref(false)

const loadFromServer = page => {
  isLoading.value = true
  CollectionService.getCollections(page).then(({ data, meta }) => {
    collections.value = collections.value.concat(data)
    collectionsMeta.value = meta
    isLoading.value = false
  })
}

loadFromServer()
</script>

<template>
  <div>
    <model-header colorClass="bg-blue-400" secondaryColorClass="bg-blue-400/50">
      <template v-slot:description>Sammlung</template>
      <template v-slot:content>
        <h2 class="text-4xl text-center">Übersicht aller Sammlungen</h2>
      </template>
    </model-header>
    <section v-if="collections && collectionsMeta" class="my-8 width-wrapper">
      <separator>
        {{ collectionsMeta.total ? collectionsMeta.total : 'Keine' }}
        {{ collectionsMeta.total == 1 ? 'Sammlung' : 'Sammlungen' }}
      </separator>
      <div v-for="collection in collections">
        <collection-section :collection="collection"></collection-section>
      </div>
      <div
        class="flex justify-center my-8"
        v-if="
          collectionsMeta &&
          collectionsMeta.current_page < collectionsMeta.last_page
        "
      >
        <button
          v-if="!isLoading"
          class="default-button"
          @click="loadFromServer(collectionsMeta.current_page + 1)"
        >
          Weitere Sammlungen anzeigen
        </button>
        <loading-spinner v-else></loading-spinner>
      </div>
    </section>
  </div>
</template>
