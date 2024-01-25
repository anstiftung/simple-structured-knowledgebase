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
    <section class="text-white bg-blue-400/50" v-if="collection">
      <div class="bg-blue-400 header-clip">
        <div class="py-12 width-wrapper">
          <h3 class="mb-2 font-normal text-center opacity-70">Sammlung</h3>
          <h2 class="text-4xl text-center">{{ collection.title }}</h2>
        </div>
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

<style scoped></style>
