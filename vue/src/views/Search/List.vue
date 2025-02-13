<script setup>
import { ref, computed, onBeforeMount } from 'vue'
import { useDebounceFn } from '@vueuse/core'

import { useRouter, useRoute } from 'vue-router'

import { useUserStore } from '@/stores/user'

import SearchService from '@/services/SearchService'
import AttachmentService from '@/services/AttachmentService'
import LoadingSpinner from '@/components/atoms/LoadingSpinner.vue'

import CollectionTable from '@/components/tables/CollectionTable.vue'
import AttachmentTable from '@/components/tables/AttachmentTable.vue'
import ArticleTable from '@/components/tables/ArticleTable.vue'

const router = useRouter()
const route = useRoute()

// If you need UserPermissions, you'll need the next three lines
const userStore = useUserStore()

const activeModels = ref(['articles'])
const creatorId = ref(null)

const searchResults = ref([])
const searchQuery = ref([])
const loading = ref(false)

const attachments = computed(() => {
  return AttachmentService.combineAttachments(
    searchResults.value.attached_urls ?? [],
    searchResults.value.attached_files ?? [],
  )
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
    true, // include trashed
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

  if (route.query.o) {
    creatorId.value = route.query.o
  }

  querySearch()
})

const switchModel = model => {
  activeModels.value = []
  activeModels.value.push(model)
  onQueryInput()
}
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
      <div v-if="userStore.id" class="flex gap-0 secondary-choice-list">
        <button
          :class="{ active: activeModels.includes('attachments') }"
          @click.prevent="switchModel('attachments')"
        >
          Anhänge
        </button>
        <button
          :class="{ active: activeModels.includes('articles') }"
          @click.prevent="switchModel('articles')"
        >
          Beiträge
        </button>
        <button
          :class="{ active: activeModels.includes('collections') }"
          @click.prevent="switchModel('collections')"
        >
          Sammlungen
        </button>
      </div>
    </div>
    <div class="flex justify-end pb-12 width-wrapper">
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
