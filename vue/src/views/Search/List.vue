<script setup>
import { ref, computed, onBeforeMount } from 'vue'
import { useDebounceFn, onClickOutside } from '@vueuse/core'

import { storeToRefs } from 'pinia'
import { useRouter, useRoute } from 'vue-router'

import { useModalStore } from '@/stores/modal'
import { useUserStore } from '@/stores/user'

import SearchService from '@/services/SearchService'
import LoadingSpinner from '@/components/atoms/LoadingSpinner.vue'

import CollectionTable from '@/components/atoms/CollectionTable.vue'
import AttachmentTable from '@/components/atoms/AttachmentTable.vue'
import ArticleTable from '@/components/atoms/ArticleTable.vue'

const router = useRouter()
const route = useRoute()
const modal = useModalStore()
// If you need UserPermissions, you'll need the next three lines
const userStore = useUserStore()
const { hasPermission } = storeToRefs(userStore)

const activeModels = ref(['articles'])
const creatorId = ref(userStore.id)

const searchResults = ref([])
const searchQuery = ref([])
const loading = ref(false)

const attachments = computed(() => {
  let attachments = []

  if (searchResults.value.attached_files) {
    attachments.push(...searchResults.value.attached_files)
  }

  if (searchResults.value.attached_urls) {
    attachments.push(...searchResults.value.attached_urls)
  }

  attachments = attachments.sort((a, b) => a.title < b.title)
  return attachments
})

const onQueryInput = useDebounceFn(() => {
  searchResults.value = []
  searchQueryUpdated(searchQuery)
  querySearch()
}, 250)

const querySearch = () => {
  loading.value = true
  SearchService.search(
    searchQuery.value,
    activeModels.value,
    false,
    creatorId.value,
  ).then(({ data, meta }) => {
    searchResults.value = data
    // searchMeta.value = meta
    // resultsVisible.value = true
    loading.value = false
  })
}

const searchQueryUpdated = searchQuery => {
  router.replace({
    query: {
      q: searchQuery.value ? encodeURI(searchQuery.value) : '',
      m: activeModels.value,
      o: creatorId.value,
    },
  })
}

onBeforeMount(() => {
  if (route.query.q) {
    searchQuery.value = route.query.q
  }

  if (route.query.m) {
    activeModels.value = route.query.m
  }

  querySearch()
})

const switchModel = model => {
  activeModels.value = []
  activeModels.value.push(model)
  onQueryInput()
}

const loadFromServer = () => {}

loadFromServer()
</script>
<template>
  <section class="bg-white">
    <div
      class="flex items-baseline justify-between pt-12 gap-x-12 width-wrapper"
    >
      <div class="grow">
        <form
          class="flex items-center gap-2 px-4 py-2 bg-white rounded-md"
          v-on:submit.prevent="querySearch()"
        >
          <input
            class="w-full outline-none placeholder:text-gray-200"
            type="text"
            placeholder="Suchen…"
            v-model="searchQuery"
            @input="onQueryInput"
            @focus="showResultsFromFocus"
          />
          <icon
            name="search"
            role="button"
            class="text-black"
            @click.prevent="querySearch()"
          />
        </form>
      </div>
      <div v-if="userStore.id" class="flex gap-4">
        <button
          class="secondary-button"
          :class="{ active: activeModels.includes('attachments') }"
          @click.prevent="switchModel('attachments')"
        >
          Anhänge
        </button>
        <button
          class="secondary-button"
          :class="{ active: activeModels.includes('articles') }"
          @click.prevent="switchModel('articles')"
        >
          Beiträge
        </button>
        <button
          class="secondary-button"
          :class="{ active: activeModels.includes('collections') }"
          @click.prevent="switchModel('collections')"
        >
          Sammlungen
        </button>
      </div>
    </div>
    <div class="flex justify-end width-wrapper pb-12">
      <label class="py-2">
        <input
          type="checkbox"
          name="is_own_content"
          v-model="creatorId"
          :true-value="userStore.id"
          :false-value="null"
          @click="onQueryInput"
        />
        nur meine Einträge anzeigen
      </label>
    </div>
    <div class="width-wrapper">
      <div class="pt-3 pb-2 pl-2 font-semibold">
        <h3 class="text-black">Suchergebnisse</h3>
      </div>
      <template v-if="loading">
        <loading-spinner />
      </template>
      <template v-else>
        <template v-if="activeModels.includes('collections')">
          <collection-table
            v-model="searchResults.collections"
            v-if="searchResults.collections"
          />
        </template>

        <template v-if="activeModels.includes('articles')">
          <article-table
            v-model="searchResults.articles"
            v-if="searchResults.articles"
          />
        </template>

        <template v-if="activeModels.includes('attachments')">
          <attachment-table v-model="attachments" v-if="attachments" />
        </template>
      </template>
    </div>
  </section>
</template>
