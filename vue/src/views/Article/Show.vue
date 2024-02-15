<script setup>
import { ref } from 'vue'

import { storeToRefs } from 'pinia'
import { useRoute } from 'vue-router'
import { useToast } from 'vue-toastification'

import { useUserStore } from '@/stores/user'

import CommentService from '@/services/CommentService'
import ArticleService from '@/services/ArticleService'

import ConfirmationToast from '@/components/atoms/ConfirmationToast.vue'
import CommentForm from '@/components/atoms/CommentForm.vue'
import ItemLine from '@/components/atoms/ItemLine.vue'
import ContentRenderer from './ContentRenderer.vue'

const toast = useToast()

const userStore = useUserStore()
const { hasPermission } = storeToRefs(userStore)

const route = useRoute()
const slug = route.params.slug
const article = ref()

const loadFromServer = () => {
  ArticleService.getArticle(slug).then(data => {
    article.value = data
    document.title = `Cowiki | ${article.value.title}`
  })
}

const deleteComment = comment => {
  toast.clear()
  const content = {
    component: ConfirmationToast,
    props: {
      message: 'Kommentar wirklich entfernen?',
    },
    listeners: {
      granted: () => {
        CommentService.deleteComment(comment).then(data => {
          loadFromServer()
        })
      },
    },
  }
  toast(content, {
    timeout: false,
    icon: false,
    closeButton: false,
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
            v-if="
              userStore.id == article.created_by.id ||
              userStore.hasPermission('update others articles')
            "
            :to="{ name: 'articleEdit', params: { slug: article.slug } }"
            >[DEBUG] Bearbeiten</router-link
          >
        </div>
      </div>
    </section>
    <section v-if="article" class="grid grid-cols-6 my-8 width-wrapper">
      <div class="col-span-4 px-8 py-16">
        <div class="prose">
          <content-renderer :content="article.content" />
        </div>
      </div>
      <div class="self-start col-span-2 px-8 py-8 border-l sticky-sidebar">
        <div class="grid grid-cols-2 mt-8">
          <div>
            <h4 class="mb-2 text-sm text-gray-300">Ersteller*in</h4>
            <p>{{ article.created_by.name }}</p>
          </div>
          <div>
            <h4 class="mb-2 text-sm text-gray-300">Zustand</h4>
            <p>{{ article.state.title }}</p>
          </div>
        </div>
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
        <h4
          class="mt-8 mb-2 text-sm text-gray-300"
          v-if="article.collections.length"
        >
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
            <div class="flex justify-between">
              <h4>{{ comment.created_by.name }}</h4>
              <a
                v-if="hasPermission('delete comments')"
                @click="deleteComment(comment)"
                >[DELETE]</a
              >
            </div>
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
