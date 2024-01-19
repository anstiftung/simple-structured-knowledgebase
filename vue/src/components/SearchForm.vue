<script setup>
import { ref, computed, onMounted, onBeforeUnmount } from 'vue'
import { useDebounceFn } from '@vueuse/core'
import SearchService from '@/services/SearchService'

import ItemLine from '@/components/atoms/ItemLine.vue'

const props = defineProps({
  placeholder: String,
})

const searchQuery = ref('')
const searchResults = ref([])
const searchMeta = ref([])
const resultsVisible = ref(false)

const escapeKeyHandler = e => {
  if (e.key === 'Escape' && resultsVisible.value) {
    resultsVisible.value = false
  }
}

onMounted(() => {
  document.addEventListener('keyup', escapeKeyHandler)
})
onBeforeUnmount(() => {
  document.removeEventListener('keyup', escapeKeyHandler)
})

const onQueryInput = useDebounceFn(() => {
  searchResults.value = []
  searchMeta.value = []
  resultsVisible.value = false
  querySearch()
}, 300)

const querySearch = () => {
  if (!searchQuery.value || searchQuery.value.length <= 3) {
    return
  }
  SearchService.search(searchQuery.value).then(({ data, meta }) => {
    searchResults.value = data
    searchMeta.value = meta

    resultsVisible.value = true
  })
}

const numSearchResults = computed(() => {
  return (
    searchMeta.value.num_articles +
    searchMeta.value.num_attached_urls +
    searchMeta.value.num_attached_files
  )
})

const resultArticlesLimited = computed(() => {
  let articles = searchResults.value.articles ?? []
  articles = articles.slice(0, Math.min(5, articles.length))
  return articles
})

const resultAttachemntsLimited = computed(() => {
  let files = searchResults.value.attached_urls ?? []
  let urls = searchResults.value.attached_files ?? []
  let attachments = files.concat(urls)
  attachments = attachments.sort((a, b) => a.created_at < b.created_at)
  attachments = attachments.slice(0, Math.min(5, attachments.length))
  return attachments
})
</script>

<template>
  <div class="relative overflow-visible">
    <form
      class="flex gap-2 px-4 py-2 bg-white rounded-md"
      v-on:submit.prevent="querySearch()"
    >
      <input
        class="w-full outline-none placeholder:text-gray-200"
        type="text"
        :placeholder="placeholder"
        v-model="searchQuery"
        @input="onQueryInput"
      />
      <img
        role="button"
        src="/icons/search.svg"
        @click.prevent="querySearch()"
      />
    </form>
    <div
      v-if="resultsVisible"
      class="min-h-[100px] max-h-[400px] overflow-y-scroll w-full absolute bg-white rounded drop-shadow-lg p-4"
    >
      <p v-if="numSearchResults == 0" class="mt-8 text-center text-gray-300">
        Keine Ergebnisse
      </p>
      <div v-else class="flex flex-col gap-2">
        <p class="text-sm italic text-gray-300">Beiträge</p>
        <item-line v-for="article in resultArticlesLimited" :model="article" />
        <p class="text-sm" v-if="searchMeta.num_articles == 0">
          Keine Ergebnisse
        </p>
        <p class="mt-4 text-sm italic text-gray-300">Anhänge</p>
        <item-line
          v-for="attachment in resultAttachemntsLimited"
          :model="attachment"
        />
        <p class="text-sm" v-if="resultAttachemntsLimited.length == 0">
          Keine Ergebnisse
        </p>
      </div>
    </div>
  </div>
</template>

<style scoped></style>
