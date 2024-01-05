<script setup>
import { ref } from 'vue'
import { storeToRefs } from 'pinia'
import { useUserStore } from '../stores/user'
import ArticleService from '@/services/ArticleService'
import AttachmentService from '@/services/AttachmentService'
import ArticleCard from '@/components/ArticleCard.vue'
import AttachmentCard from '@/components/AttachmentCard.vue'

const recentArticles = ref([])
const recentAttachedUrls = ref([])
const recentAttachedFiles = ref([])

// If you need UserPermissions, you'll need the next three lines
const userStore = useUserStore()
const { hasPermission } = storeToRefs(userStore)

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
    <!-- todo: generalize -->
    <div class="py-12 width-wrapper">
      <div v-if="userStore.id">
        <section class="mb-4">
          <p><strong>Benutzername:</strong> {{ userStore.name }}</p>
          <p><strong>E-Mail Adresse:</strong> {{ userStore.email }}</p>
        </section>
        <section class="mb-4">
          <button class="default-button">Anhang erstellen</button>
        </section>
        <section class="mb-4">
          <button class="default-button">Beitrag erstellen</button>
        </section>
        <section class="mb-4" v-if="hasPermission('add collections')">
          <button class="default-button">Sammlung erstellen</button>
        </section>

        <section class="mb-4">
          <router-link
            :to="{ name: 'logout' }"
            class="inline-block default-button"
            >Abmelden</router-link
          >
        </section>

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
      <div v-else>
        <p>
          <strong>Fehler:</strong> Du bist nicht authorisiert diese Inhalte zu
          sehen.
        </p>
      </div>
    </div>
  </section>
</template>
