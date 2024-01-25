<script setup>
import { ref } from 'vue'
import ArticleService from '@/services/ArticleService'

const articles = ref([])
const articlesMeta = ref()

const loadFromServer = page => {
  ArticleService.getArticles(page).then(({ data, meta }) => {
    articles.value = articles.value.concat(data)
    articlesMeta.value = meta
  })
}

loadFromServer()
</script>

<template>
  <section class="bg-white">
    <!-- todo: generalize -->
    <div class="py-12 width-wrapper">
      <h2 class="mb-4 text-2xl">Alle Beiträge</h2>
      <div class="grid grid-cols-3 gap-4">
        <router-link
          :to="{
            name: 'article',
            params: { slug: article.slug },
          }"
          v-for="article in articles"
          >{{ article.title }}</router-link
        >
      </div>
      <template
        v-if="
          articlesMeta && articlesMeta.current_page < articlesMeta.last_page
        "
      >
        <a
          class="block mt-8 text-blue"
          @click.prevent="loadFromServer(articlesMeta.current_page + 1)"
          >Weitere Daten laden</a
        >
      </template>
    </div>
  </section>
</template>
