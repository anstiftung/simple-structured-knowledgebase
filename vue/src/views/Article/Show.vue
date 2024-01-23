<script setup>
import { ref } from 'vue'
import ArticleService from '@/services/ArticleService'
import AttachmentCard from '@/components/AttachmentCard.vue'

import { useRoute } from 'vue-router'

const route = useRoute()
const slug = route.params.slug
const article = ref()

const loadFromServer = () => {
  ArticleService.getArticle(slug)
    .then(data => {
      article.value = data
      document.title = `Cowiki | ${article.value.title}`
    })
    .catch(error => {
      // ? do anything here?s
    })
}

loadFromServer()
</script>

<template>
  <div>
    <section class="text-white bg-orange" v-if="article">
      <div class="py-12 width-small-wrapper">
        <h4 class="text-sm text-white uppercase">Beitrag</h4>
        <h2 class="mb-4 text-3xl text-white">{{ article.title }}</h2>
        <p>{{ article.description }}</p>
        <p class="mt-4 text-sm">
          Erstellt am {{ $filters.formatedDateTime(article.created_at) }}
        </p>
        <p class="mt-4 text-sm">
          Zuletzt bearbeitet {{ $filters.formatedDateTime(article.updated_at) }}
        </p>
        <router-link
          :to="{ name: 'articleEdit', params: { slug: article.slug } }"
          >[DEBUG] Bearbeiten</router-link
        >
      </div>
    </section>
    <section v-if="article" class="my-8 width-wrapper">
      <h2>Inhalt</h2>
      <div v-html="article.content"></div>
      <h2>Anhänge</h2>
      <div class="grid grid-cols-3 gap-4">
        <attachment-card
          v-for="attachment in article.attached_urls.concat(
            article.attached_files,
          )"
          :attachment="attachment"
        />
      </div>
    </section>
  </div>
</template>
