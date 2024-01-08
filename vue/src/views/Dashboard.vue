<script setup>
import { ref } from 'vue'
import { storeToRefs } from 'pinia'

import { useModalStore } from '@/stores/modal'
import { useUserStore } from '@/stores/user'

import ArticleService from '@/services/ArticleService'
import AttachmentService from '@/services/AttachmentService'

import AddAttachment from '@/components/attachments/AddAttachment.vue'
import ArticleCard from '@/components/ArticleCard.vue'
import AttachmentCard from '@/components/AttachmentCard.vue'

const recentArticles = ref([])
const recentAttachedUrls = ref([])
const recentAttachedFiles = ref([])

const modal = useModalStore()
// If you need UserPermissions, you'll need the next three lines
const userStore = useUserStore()
const { hasPermission } = storeToRefs(userStore)

const showCreateAttachmentModal = () => {
  modal.open(AddAttachment, [
    {
      label: 'Save',
      callback: dataFromView => {
        console.log(dataFromView)
        modal.close()
      },
    },
  ])
}
userStore.initUser().then(() => {
  loadFromServer()
})

const loadFromServer = () => {
  ArticleService.getArticles(1, userStore.id).then(({ data, meta }) => {
    recentArticles.value = data.slice(0, Math.min(5, data.length))
  })

  AttachmentService.getAttachmentUrls(1, userStore.id).then(
    ({ data, meta }) => {
      recentAttachedUrls.value = data.slice(0, Math.min(5, data.length))
    },
  )

  AttachmentService.getAttachmentFiles(1, userStore.id).then(
    ({ data, meta }) => {
      recentAttachedFiles.value = data.slice(0, Math.min(5, data.length))
    },
  )
}
</script>
<template>
  <section class="bg-white">
    <div class="flex items-baseline justify-between py-12 width-wrapper">
      <h2 class="text-2xl font-bold">Dashboard</h2>
      <div v-if="userStore.id" class="flex gap-4">
        <button
          class="default-button"
          @click.prevent="showCreateAttachmentModal"
        >
          Anhang erstellen
        </button>
        <button class="default-button">Beitrag erstellen</button>
        <button v-if="hasPermission('add collections')" class="default-button">
          Sammlung erstellen
        </button>
      </div>
    </div>
    <div class="width-wrapper">
      <h2>Deine letzten Artikel</h2>
      <div class="grid grid-cols-3 gap-4 py-8">
        <article-card v-for="article in recentArticles" :article="article" />
      </div>
      <h2>Deine letzten Anhänge (Dateien)</h2>
      <div class="grid grid-cols-3 gap-4 py-8">
        <attachment-card
          v-for="attachedFile in recentAttachedFiles"
          :attachment="attachedFile"
        />
      </div>
      <h2>Deine letzten Anhänge (Urls)</h2>
      <div class="grid grid-cols-3 gap-4 py-8">
        <attachment-card
          v-for="attachedUrl in recentAttachedUrls"
          :attachment="attachedUrl"
        />
      </div>
    </div>
  </section>
</template>
