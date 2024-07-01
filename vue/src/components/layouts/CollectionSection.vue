<script setup>
import ArticleCard from '@/components/atoms/ArticleCard.vue'

const props = defineProps({
  collection: Object,
})

const limitedArticles = collection => {
  return collection.articles.slice(0, Math.min(3, collection.articles.length))
}
</script>

<template>
  <section class="py-12">
    <h3 class="mb-4 text-xl text-blue-600">{{ collection.title }}</h3>
    <div class="relative grid sm:grid-cols-3 gap-12">
      <div class="flex flex-col sm:col-span-1 gap-6 mt-4">
        <p class="text-blue-600 line-clamp-5">
          {{ collection.description }}
        </p>
        <p class="text-sm text-blue-600">
          <span class="font-bold text-orange"
            >{{ collection.articles.length }}
            {{ collection.articles.length == 1 ? 'Beitrag' : 'Beiträge' }}</span
          >
          <span> in dieser Sammlung</span>
        </p>
        <router-link :to="collection.url" class="self-start default-button"
          >Sammlung öffnen</router-link
        >
      </div>
      <div class="grid sm:grid-cols-3 sm:col-span-2 gap-12">
        <article-card
          v-for="article in limitedArticles(collection)"
          :article="article"
        ></article-card>
      </div>
      <div
        class="absolute -right-[50px] top-1/2"
        v-if="collection.articles.length > 3"
      >
        <router-link :to="collection.url">
          <img class="max-w-8" src="/icons/recipesStacked.svg" />
        </router-link>
      </div>
    </div>
  </section>
</template>

<style scoped></style>
