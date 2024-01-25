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
    <section class="text-white bg-orange/50" v-if="article">
      <div class="bg-orange header-clip">
        <div class="py-12 width-wrapper">
          <h3 class="mb-2 font-normal text-center opacity-70">Beitrag</h3>
          <h2 class="text-4xl text-center">{{ article.title }}</h2>
          <router-link
            :to="{ name: 'articleEdit', params: { slug: article.slug } }"
            >[DEBUG] Bearbeiten</router-link
          >
        </div>
      </div>
    </section>
    <section v-if="article" class="my-8 width-wrapper">
      <h2>Inhalt</h2>
      <div class="prose" v-html="article.content"></div>
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
