<script setup>
import { ref, computed } from 'vue'
import { storeToRefs } from 'pinia'

import { useModalStore } from '@/stores/modal'
import { useUserStore } from '@/stores/user'

import ArticleService from '@/services/ArticleService'
import AttachmentService from '@/services/AttachmentService'

import AddAttachment from '@/components/attachments/AddAttachment.vue'
import SearchForm from '@/components/SearchForm.vue'

const recentArticles = ref([])
const recentAttachedUrls = ref([])
const recentAttachedFiles = ref([])

const modal = useModalStore()
// If you need UserPermissions, you'll need the next three lines
const userStore = useUserStore()
const { hasPermission } = storeToRefs(userStore)

const showCreateAttachmentModal = () => {
  modal.open(AddAttachment, () => {
    loadFromServer()
  })
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

const activities = computed(() => {
  let activities = recentArticles.value
    .concat(recentAttachedFiles.value)
    .concat(recentAttachedUrls.value)
  activities = activities.sort((a, b) => a.created_at < b.created_at)
  return activities
})
</script>
<template>
  <section class="bg-white">
    <div
      class="flex items-baseline justify-between py-12 gap-x-12 width-wrapper"
    >
      <search-form
        placeholder="Suche in meinen Beiträgen, Anhängen und Sammlungen"
        class="grow"
      />
      <div v-if="userStore.id" class="flex gap-4">
        <button
          class="default-button"
          @click.prevent="showCreateAttachmentModal"
        >
          Anhang erstellen
        </button>
        <router-link
          tag="button"
          :to="{ name: 'articleCreate' }"
          class="default-button"
          >Beitrag erstellen</router-link
        >
        <button v-if="hasPermission('add collections')" class="default-button">
          Sammlung erstellen
        </button>
      </div>
    </div>
    <div class="grid grid-cols-2 divide-x width-wrapper">
      <div class="">
        <div class="pt-3 pb-2 pl-2 font-semibold border-y">
          <h3 class="text-black">Letzte Aktivitäten</h3>
        </div>
        <div class="py-4 pl-2" v-if="activities">
          <p class="mb-2" v-for="activity in activities">
            <span>{{
              activity.type == 'Article' ? 'Beitrag ' : 'Anhang '
            }}</span>
            <router-link
              class="font-semibold text-orange"
              v-if="activity.type == 'Article'"
              :to="{
                name: 'article',
                params: { slug: activity.slug },
              }"
            >
              {{ activity.title }}
            </router-link>
            <span v-else class="font-semibold text-green">
              {{ activity.title }}
            </span>
            <span> erstellt</span>
            <span class="inline-block ml-2 text-gray-200">{{
              $filters.formatedDate(activity.created_at)
            }}</span>
          </p>
        </div>
      </div>
      <div class="">
        <div class="pt-3 pb-2 pl-2 border-y">
          <h3 class="font-semibold text-black">
            Anhänge mit unzureichenden Metadaten
          </h3>
          <p class="text-gray-200">
            Solche Anhänge können nicht genutzt und veröffentlicht werden.
          </p>
        </div>
        <div class="min-h-[200px] py-4 pl-2">@todo ;)</div>
        <div class="pt-3 pb-2 pl-2 border-y">
          <h3 class="font-semibold text-black">
            Sammlungen auf der Startseite
          </h3>
        </div>
        <div class="min-h-[200px] py-4 pl-2">@todo ;)</div>
      </div>
    </div>
  </section>
</template>
