<script setup>
import { ref, inject, computed } from 'vue'

import { storeToRefs } from 'pinia'
import { useRoute, useRouter } from 'vue-router'

import { useUserStore } from '@/stores/user'

import CommentService from '@/services/CommentService'
import ArticleService from '@/services/ArticleService'

import CommentForm from '@/components/forms/CommentForm.vue'
import ItemLine from '@/components/atoms/ItemLine.vue'
import ContentRenderer from '@/components/atoms/ContentRenderer.vue'
import ModelHeader from '@/components/layouts/ModelHeader.vue'
import AttachmentCard from '@/components/atoms/AttachmentCard.vue'
import ClapButton from '@/components/atoms/ClapButton.vue'

const userStore = useUserStore()
const { hasPermission } = storeToRefs(userStore)

const route = useRoute()
const router = useRouter()

const slug = route.params.slug
const article = ref()

const $toast = inject('$toast')

const loadFromServer = () => {
  ArticleService.getArticle(slug)
    .then(data => {
      article.value = data
      document.title = `Cowiki | ${article.value.title}`
    })
    .catch(error => {
      router.push({ name: 'not-found' })
    })
}

const deleteArticle = (forceDelete = false) => {
  $toast.confirm(
    forceDelete
      ? 'Beitrag endgültig löschen? Diese Aktion kann nicht rückgängig gemacht werden.'
      : 'Beitrag wirklich löschen?',
    () => {
      ArticleService.deleteArticle(article.value, forceDelete).then(data => {
        router.push({ name: 'dashboard' })
      })
    },
  )
}

const deleteComment = comment => {
  $toast.confirm('Kommentar wirklich entfernen?', () => {
    CommentService.deleteComment(comment).then(data => {
      loadFromServer()
    })
  })
}

const unifiedAttachments = computed(() => {
  let attachments = article.value.attached_files.concat(
    article.value.attached_urls,
  )
  attachments = attachments.sort((a, b) => a.created_at < b.created_at)
  return attachments
})

loadFromServer()
</script>

<template>
  <div>
    <model-header
      colorClass="bg-orange"
      secondaryColorClass="bg-orange/50"
      v-if="article"
    >
      <template v-slot:description>Beitrag</template>
      <template v-slot:content>
        <h2 class="text-4xl text-center break-words hyphens-auto">
          {{ article.title }}
        </h2>
        <router-link
          v-if="
            !article.deleted_at &&
            (userStore.id == article.created_by.id ||
              hasPermission('update others articles'))
          "
          class="block pt-2 opacity-70"
          :to="{ name: 'articleEdit', params: { slug: article.slug } }"
          ><icon name="edit" /><span class="inline-block ml-1 underline"
            >Bearbeiten</span
          ></router-link
        >
      </template>
    </model-header>

    <section v-if="article" class="grid sm:grid-cols-6 my-8 width-wrapper">
      <div class="col-span-4 px-8 py-16">
        <div class="prose">
          <content-renderer :content="article.content" v-if="article.content" />
        </div>
        <div
          class="mt-20 text-center clear-both"
          v-if="userStore.id && !article.deleted_at"
        >
          <h3 class="text-lg">
            Dir hat der Beitrag gefallen? Lass einen clap da.
          </h3>
          <clap-button :article="article"></clap-button>
        </div>
      </div>
      <div
        class="self-start col-span-2 px-8 py-8 border-l sticky-sidebar min-h-full-without-header"
      >
        <div class="flex flex-col gap-8">
          <div v-if="article.approved">
            <icon name="approved" class="mr-2 text-green size-6"></icon>
            geprüfter Inhalt
          </div>
          <div class="grid grid-cols-2">
            <div class="flex items-center gap-2">
              <icon name="clap" class="text-gray-300 size-6"></icon>
              <span>{{ article.claps }}</span>
            </div>
            <div class="flex items-center gap-2">
              <icon name="comment" class="text-gray-300 size-6"></icon>
              <span>{{
                article.comments ? article.comments.length : '0'
              }}</span>
            </div>
          </div>
          <div class="grid grid-cols-2">
            <div>
              <h4 class="mb-2 text-sm text-gray-300">Ersteller*in</h4>
              <p>
                <router-link
                  :to="{
                    name: 'profile',
                    params: { id: article.created_by.id },
                  }"
                  class="text-blue-600 font-semibold"
                >
                  {{ article.created_by.name }}
                </router-link>
              </p>
            </div>
            <div>
              <h4 class="mb-2 text-sm text-gray-300">Zustand</h4>
              <p>{{ article.state.title }}</p>
            </div>
          </div>
          <div class="grid grid-cols-2">
            <div>
              <h4 class="mb-2 text-sm text-gray-300">erstellt am</h4>
              <p>{{ $filters.formatedDate(article.created_at) }}</p>
            </div>
            <div>
              <h4 class="mb-2 text-sm text-gray-300">geändert am</h4>
              <p>{{ $filters.formatedDate(article.updated_at) }}</p>
            </div>
          </div>
          <div v-if="article.deleted_at">
            <h4 class="mb-2 text-sm text-gray-300">als gelöscht markiert</h4>
            <p>{{ $filters.formatedDateTime(article.deleted_at) }}</p>
          </div>
          <div
            class="cursor-pointer"
            v-if="
              !article.deleted_at &&
              (hasPermission('delete others articles') ||
                article.state?.key == 'draft')
            "
            @click="deleteArticle(false)"
          >
            <icon name="trash" class="text-black" />
            <span class="inline-block ml-2 underline">Löschen</span>
          </div>
          <div
            class="cursor-pointer"
            v-if="article.deleted_at && hasPermission('force delete articles')"
            @click="deleteArticle(true)"
          >
            <icon name="trash" class="text-black" />
            <span class="inline-block ml-2 underline">Endgültig Löschen</span>
          </div>
          <div>
            <h4
              class="mb-2 text-sm text-gray-300"
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
        </div>
      </div>
    </section>

    <section
      v-if="article && unifiedAttachments.length > 0"
      class="my-8 width-wrapper"
    >
      <h3 class="pb-2 border-b">Alle verwendeten Anhänge dieses Beitrags</h3>
      <div class="grid sm:grid-cols-3 gap-8 py-8 auto-rows-[1fr]">
        <attachment-card
          v-for="attachment in unifiedAttachments"
          :attachment="attachment"
        ></attachment-card>
      </div>
    </section>
    <section v-if="article" class="my-8 width-wrapper">
      <h3 class="pb-2 border-b">
        Kommentare ({{ article.comments ? article.comments.length : '0' }})
      </h3>
      <div class="grid sm:grid-cols-6">
        <div class="col-span-4 divide-y">
          <div class="py-8" v-for="comment in article.comments">
            <div class="flex justify-between">
              <h4>{{ comment.created_by.name }}</h4>
              <a
                v-if="hasPermission('delete comments')"
                @click="deleteComment(comment)"
                title="Kommentar Löschen"
                ><icon name="trash" class="size-5"></icon
              ></a>
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
