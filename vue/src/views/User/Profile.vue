<script setup>
import { ref, computed } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { storeToRefs } from 'pinia'
import { useUserStore } from '@/stores/user'
import UserService from '@/services/UserService'
import AttachmentService from '@/services/AttachmentService'
import ItemLine from '@/components/atoms/ItemLine.vue'
import CollectionTable from '@/components/tables/CollectionTable.vue'
import ArticleTable from '@/components/tables/ArticleTable.vue'
import AttachmentTable from '@/components/tables/AttachmentTable.vue'

const userStore = useUserStore()
const { hasPermission } = storeToRefs(userStore)
const router = useRouter()
const route = useRoute()
const id = route.params.id
const user = ref()

const loadFromServer = () => {
  let loadFunction = null
  loadFunction = UserService.getUser(id)

  loadFunction
    .then(data => {
      user.value = data
      document.title = `Cowiki | ${user.value.title}`
    })
    .catch(error => {
      router.push({ name: 'not-found' })
    })
}

loadFromServer()

const attachments = computed(() => {
  return AttachmentService.combineAttachments(
    user.value.attached_urls ?? [],
    user.value.attached_files ?? [],
  )
})

const numAttachments = computed(() => {
  let num = 0
  num += user.value.attached_urls.length ?? 0
  num += user.value.attached_files.length ?? 0
  return num
})

const numCollections = computed(() => {
  return user.value.collections.length ?? 0
})
const numArticles = computed(() => {
  return user.value.articles.length ?? 0
})
</script>
<template>
  <div class="width-wrapper">
    <h1 v-if="user" class="mt-2 mb-4 text-xl text-blue-600">
      <icon name="user" /> {{ user.name }}
    </h1>
    <div class="text-blue-600 mb-4">
      <p>
        Diese Benutzer_in hat insgesamt
        <span class="font-semibold"
          >{{ numAttachments }} <span class="text-green">Anhänge</span></span
        >
        zum CoWiki hinzugefügt.
      </p>
      <p>
        Es wurden
        <span class="font-semibold"
          >{{ numArticles }} <span class="text-red">Beiträge</span></span
        >
        der Nutzer:in veröffentlicht.
      </p>
      <p v-if="user.collections">
        Die Nutzer:in hat
        <span class="font-semibold"
          >{{ numCollections }}
          <span class="font-semibold">Sammlungen</span></span
        >
        angelegt.
      </p>
    </div>

    <h2 class="mb-4 text-lg text-blue-600" v-if="user.collections">
      Sammlungen der Benutzer:in
    </h2>
    <collection-table
      v-model="user.collections"
      v-if="user.collections"
      class="mb-6"
    />

    <h2 v-if="user" class="mb-4 text-lg text-blue-600">
      Beiträge der Benutzer:in
    </h2>
    <article-table v-model="user.articles" v-if="user.articles" class="mb-6" />

    <h2 v-if="user" class="mb-4 text-lg text-blue-600">
      Anhänge der Benutzer:in
    </h2>
    <attachment-table v-model="attachments" v-if="attachments" class="mb-6" />
  </div>
</template>
