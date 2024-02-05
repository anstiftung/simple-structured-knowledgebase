<script setup>
import { ref } from 'vue'
import ArticleService from '@/services/ArticleService'
import AttachmentCard from '@/components/AttachmentCard.vue'
import CommentForm from '@/components/atoms/CommentForm.vue'
import ItemLine from '@/components/atoms/ItemLine.vue'
import { useRoute } from 'vue-router'
import { useUserStore } from '@/stores/user'

const userStore = useUserStore()

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
    <section v-if="article" class="grid grid-cols-6 my-8 width-wrapper">
      <div class="col-span-4 px-8 py-16">
        <div class="prose" v-html="article.content"></div>
        <!-- <h2>Anhänge</h2>
        <div class="grid grid-cols-3 gap-4">
          <attachment-card
            v-for="attachment in article.attached_urls.concat(
              article.attached_files,
            )"
            :attachment="attachment"
          />
        </div> -->
      </div>
      <div class="self-start col-span-2 px-8 py-8 border-l">
        <h4 class="mb-2 text-sm text-gray-300">Ersteller*in</h4>
        <p>{{ article.created_by.name }}</p>
        <div class="grid grid-cols-2 mt-8">
          <div>
            <h4 class="mb-2 text-sm text-gray-300">erstellt am</h4>
            <p>{{ $filters.formatedDate(article.created_at) }}</p>
          </div>
          <div>
            <h4 class="mb-2 text-sm text-gray-300">geändert am</h4>
            <p>{{ $filters.formatedDate(article.updated_at) }}</p>
          </div>
        </div>
        <h4 class="mt-8 mb-2 text-sm text-gray-300">
          andere Sammlungen mit diesem Beitrag
        </h4>
        <p>
          <item-line
            v-for="collection in article.collections"
            :navigate="true"
            :showType="false"
            :model="collection"
            class="mb-2"
          ></item-line>
        </p>
      </div>
    </section>

    <section v-if="article" class="my-8 width-wrapper">
      <h3 class="pb-2 border-b">
        Kommentare ({{ article.comments ? article.comments.length : '0' }})
      </h3>
      <div class="grid grid-cols-6">
        <div class="col-span-4 divide-y">
          <div class="py-8" v-for="comment in article.comments">
            <h4>{{ comment.created_by.name }}</h4>
            <p class="text-gray-200">
              {{ $filters.formatedDate(comment.created_at) }}
            </p>
            <p class="mt-4 whitespace-pre-wrap">{{ comment.content }}</p>
          </div>
          <comment-form
            v-if="userStore.id"
            :article="article"
            @save="loadFromServer"
          />
        </div>
      </div>
    </section>
  </div>
</template>
