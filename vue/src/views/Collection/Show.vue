<script setup>
import { ref } from 'vue'
import CollectionService from '@/services/CollectionService'
import ArticleCard from '@/components/ArticleCard.vue'

import { useRoute } from 'vue-router'

const route = useRoute()
const slug = route.params.slug
const collection = ref()

const loadFromServer = () => {
  CollectionService.getCollection(slug)
    .then(data => {
      collection.value = data
      document.title = `Cowiki | ${collection.value.title}`
    })
    .catch(error => {
      // ? do anything here?s
    })
}

loadFromServer()
</script>

<template>
  <div>
    <section class="text-white bg-blue-400" v-if="collection">
      <div class="py-12 width-small-wrapper">
        <h4 class="text-sm text-white uppercase">Sammlung</h4>
        <h2 class="mb-4 text-3xl text-white">{{ collection.title }}</h2>
        <p>{{ collection.description }}</p>
        <p class="mt-4 text-sm">
          Erstellt am {{ $filters.formatedDateTime(collection.created_at) }}
        </p>
        <p class="mt-4 text-sm">
          Zuletzt bearbeitet
          {{ $filters.formatedDateTime(collection.updated_at) }}
        </p>
        <router-link
          :to="{ name: 'collectionEdit', params: { slug: collection.slug } }"
          >[DEBUG] Bearbeiten</router-link
        >
      </div>
    </section>
    <section v-if="collection?.articles" class="my-8 width-wrapper">
      <h2>Beiträge</h2>
      <div class="grid grid-cols-3 gap-4">
        <article-card
          v-for="article in collection.articles"
          :article="article"
        />
      </div>
    </section>
  </div>
</template>
