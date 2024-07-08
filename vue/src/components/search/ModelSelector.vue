<script setup>
import { ref, computed, onMounted } from 'vue'
import { useDebounceFn } from '@vueuse/core'

import AttachmentService from '@/services/AttachmentService'
import SearchService from '@/services/SearchService'

import LoadingSpinner from '@/components/atoms/LoadingSpinner.vue'
import QuickSearchResult from '@/components/search/QuickSearchResult.vue'

const props = defineProps({
  modelType: String,
})
const emit = defineEmits(['done'])

const loading = ref(false)
const searchQuery = ref('')
const searchResults = ref([])
const searchMeta = ref(null)
const searchInput = ref(null)

onMounted(() => {
  searchInput.value.focus()
  querySearch()
})

const onQueryInput = useDebounceFn(() => {
  searchResults.value = []
  searchMeta.value = null
  querySearch()
}, 250)

const querySearch = () => {
  loading.value = true
  SearchService.search(searchQuery.value, [props.modelType]).then(
    ({ data, meta }) => {
      searchResults.value = data
      searchMeta.value = meta
      loading.value = false
    },
  )
}

const selectModel = model => {
  emit('done', model)
}

const modelLabel = computed(() => {
  switch (props.modelType) {
    case 'articles':
      return 'Beiträge'
      break
    case 'collections':
      return 'Sammlungen'
    case 'attachments':
      return 'Anhänge'
    case 'images':
      return 'Bilder'
    default:
      return '[Unkown]'
      break
  }
})

const modelResults = computed(() => {
  if (searchResults.value[props.modelType]) {
    return searchResults.value[props.modelType]
  } else if (props.modelType == 'attachments') {
    return AttachmentService.combineAttachments(
      searchResults.value.attached_urls,
      searchResults.value.attached_files,
    )
  } else {
    console.warn(
      'Unable to extract search results for modelType',
      props.modelType,
      searchResults.value,
    )
  }
})
</script>

<template>
  <div class="relative overflow-visible bg-white rounded-md">
    <form
      class="flex items-center gap-2 px-4 py-2"
      v-on:submit.prevent="querySearch()"
    >
      <icon name="search" role="button" @click.prevent="querySearch()" />
      <input
        class="w-full outline-none placeholder:text-gray-200"
        type="text"
        placeholder="Suchen"
        v-model="searchQuery"
        @input="onQueryInput"
        ref="searchInput"
      />
    </form>
    <div class="min-h-[100px] max-h-[400px] bg-white overflow-y-scroll">
      <loading-spinner v-if="loading"></loading-spinner>
      <div v-else class="flex flex-col gap-2 p-4">
        <p class="text-sm italic text-gray-300">{{ modelLabel }}</p>
        <p v-if="!searchMeta || searchMeta.num_results == 0">
          Keine Ergebnisse
        </p>
        <quick-search-result
          :modelType="props.modelType"
          :results="modelResults"
          @result-selected="item => selectModel(item)"
          v-else
        />
      </div>
    </div>
  </div>
</template>

<style scoped></style>
