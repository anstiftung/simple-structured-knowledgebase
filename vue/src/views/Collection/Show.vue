<script setup>
import { ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { storeToRefs } from 'pinia'
import { useUserStore } from '@/stores/user'

import CollectionService from '@/services/CollectionService'
import ArticleCard from '@/components/ArticleCard.vue'
import ModelHeader from '@/components/layouts/ModelHeader.vue'
import Separator from '@/components/layouts/Separator.vue'

const userStore = useUserStore()
const { hasPermission } = storeToRefs(userStore)
const router = useRouter()
const route = useRoute()
const slug = route.params.slug
const collection = ref()

const loadFromServer = () => {
  let loadFunction = null
  if (slug == 'catchAll') {
    loadFunction = CollectionService.getCatchAllCollection()
  } else {
    loadFunction = CollectionService.getCollection(slug)
  }

  loadFunction
    .then(data => {
      collection.value = data
      document.title = `Cowiki | ${collection.value.title}`
    })
    .catch(error => {
      router.push({ name: 'not-found' })
    })
}

loadFromServer()
</script>

<template>
  <div>
    <model-header
      colorClass="bg-blue-400"
      secondaryColorClass="bg-blue-400/50"
      v-if="collection"
    >
      <template v-slot:description>Sammlung</template>
      <template v-slot:content>
        <h2 class="text-4xl text-center">{{ collection.title }}</h2>
        <router-link
          class="block pt-2 opacity-70"
          v-if="collection.slug && hasPermission('edit collections')"
          :to="{ name: 'collectionEdit', params: { slug: collection.slug } }"
          ><icon name="edit" /><span class="inline-block ml-1 underline"
            >Bearbeiten</span
          ></router-link
        >
      </template>
    </model-header>
    <section v-if="collection?.articles" class="my-8 width-wrapper">
      <p class="my-12">{{ collection.description }}</p>
      <separator>
        {{ collection.articles.length ? collection.articles.length : 'Keine' }}
        {{ collection.articles.length == 1 ? 'Beitrag' : 'Beiträge' }}
      </separator>
      <div class="grid grid-cols-4 gap-4">
        <article-card
          v-for="article in collection.articles"
          :article="article"
        />
      </div>
    </section>
  </div>
</template>

<style scoped></style>
