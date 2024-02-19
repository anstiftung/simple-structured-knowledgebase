<script setup>
import { ref, computed, onMounted, onBeforeUnmount } from 'vue'
import { useDebounceFn, onClickOutside } from '@vueuse/core'

import SearchService from '@/services/SearchService'
import ItemLine from '@/components/atoms/ItemLine.vue'
import LoadingSpinner from './atoms/LoadingSpinner.vue'

const props = defineProps({
  placeholder: String,
  initialQuery: String,
  types: {
    type: Array,
    default: ['articles', 'collections', 'attachments'],
  },
  onlyPublished: {
    type: Boolean,
    default: true,
  },
  navigate: {
    type: Boolean,
    default: true,
  },
})

const emit = defineEmits(['queryChanged', 'selected'])

const searchQuery = ref('')
const searchResults = ref([])
const searchMeta = ref([])

const resultsVisible = ref(false)
const loading = ref(false)
const resultsOverlay = ref(null)

const escapeKeyHandler = e => {
  if (e.key === 'Escape' && resultsVisible.value) {
    resultsVisible.value = false
  }
}
onClickOutside(resultsOverlay, () => (resultsVisible.value = false))

const showResultsFromFocus = () => {
  resultsVisible.value = true
  // the results may have been changed in the meantime
  querySearch()
}

onMounted(() => {
  // set inital query
  if (props.initialQuery) {
    searchQuery.value = props.initialQuery
  }
  document.addEventListener('keyup', escapeKeyHandler)
})
onBeforeUnmount(() => {
  document.removeEventListener('keyup', escapeKeyHandler)
})

const onQueryInput = useDebounceFn(() => {
  searchResults.value = []
  searchMeta.value = []
  querySearch()
}, 250)

const querySearch = () => {
  loading.value = true
  SearchService.search(
    searchQuery.value,
    props.types,
    props.onlyPublished,
  ).then(({ data, meta }) => {
    searchResults.value = data
    searchMeta.value = meta
    resultsVisible.value = true
    loading.value = false
  })
}

const resultArticlesLimited = computed(() => {
  let articles = searchResults.value.articles ?? []
  articles = articles.slice(0, Math.min(5, articles.length))
  return articles
})

const resultCollectionsLimited = computed(() => {
  let collections = searchResults.value.collections ?? []
  collections = collections.slice(0, Math.min(5, collections.length))
  return collections
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
      class="flex items-center gap-2 px-4 py-2 bg-white rounded-md"
      v-on:submit.prevent="querySearch()"
    >
      <input
        class="w-full outline-none placeholder:text-gray-200"
        type="text"
        :placeholder="placeholder"
        v-model="searchQuery"
        @input="onQueryInput"
        @focus="showResultsFromFocus"
      />
      <icon
        name="search"
        role="button"
        class="fill-gray-400"
        @click.prevent="querySearch()"
      />
    </form>
    <div
      v-if="resultsVisible"
      ref="resultsOverlay"
      class="min-h-[100px] max-h-[400px] overflow-y-scroll w-full absolute bg-white rounded drop-shadow-lg p-4 z-50"
    >
      <loading-spinner class="my-4" v-if="loading"></loading-spinner>
      <div v-else class="flex flex-col gap-2">
        <template v-if="props.types.includes('articles')">
          <p class="text-sm italic text-gray-300">Beiträge</p>
          <item-line
            v-for="article in resultArticlesLimited"
            :model="article"
            :navigate="props.navigate"
            @click="$emit('selected', article)"
          />
          <p class="text-sm" v-if="resultArticlesLimited.length == 0">
            Keine Ergebnisse
          </p>
        </template>

        <template v-if="props.types.includes('collections')">
          <p class="mt-4 text-sm italic text-gray-300">Sammlungen</p>
          <item-line
            v-for="collection in resultCollectionsLimited"
            :model="collection"
            :navigate="props.navigate"
            @click="$emit('selected', collection)"
          />
          <p class="text-sm" v-if="resultCollectionsLimited.length == 0">
            Keine Ergebnisse
          </p>
        </template>

        <template v-if="props.types.includes('attachments')">
          <p class="mt-4 text-sm italic text-gray-300">Anhänge</p>
          <item-line
            v-for="attachment in resultAttachemntsLimited"
            :model="attachment"
            :navigate="props.navigate"
            @click="$emit('selected', attachment)"
          />
          <p class="text-sm" v-if="resultAttachemntsLimited.length == 0">
            Keine Ergebnisse
          </p>
        </template>
      </div>
    </div>
  </div>
</template>

<style scoped></style>
