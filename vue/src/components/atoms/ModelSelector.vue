<script setup>
import { ref, computed, onMounted } from 'vue'
import { useDebounceFn } from '@vueuse/core'
import SearchService from '@/services/SearchService'

import ItemLine from '@/components/atoms/ItemLine.vue'

const props = defineProps({
  modelType: String,
})
const emit = defineEmits(['done'])

const searchQuery = ref('')
const searchResults = ref([])
const searchMeta = ref(null)
const searchInput = ref(null)

onMounted(() => {
  searchInput.value.focus()
})

const onQueryInput = useDebounceFn(() => {
  searchResults.value = []
  searchMeta.value = null
  querySearch()
}, 300)

const querySearch = () => {
  if (!searchQuery.value || searchQuery.value.length <= 3) {
    return
  }
  SearchService.search(searchQuery.value).then(({ data, meta }) => {
    searchResults.value = data
    searchMeta.value = meta
  })
}

const selectModel = model => {
  emit('done', model)
}

const modelLabel = computed(() => {
  switch (props.modelType) {
    case 'article':
      return 'Beiträge'
      break
    case 'attachment':
      return 'Anhänge'
    default:
      return '[Modeltype]'
      break
  }
})
</script>

<template>
  <div class="relative overflow-visible bg-white rounded-md">
    <form class="flex gap-2 px-4 py-2" v-on:submit.prevent="querySearch()">
      <input
        class="w-full outline-none placeholder:text-gray-200"
        type="text"
        placeholder="Suchen"
        v-model="searchQuery"
        @input="onQueryInput"
        ref="searchInput"
      />
      <img
        role="button"
        src="/icons/search.svg"
        @click.prevent="querySearch()"
      />
    </form>
    <div class="min-h-[100px] max-h-[400px] overflow-y-scroll bg-white p-4">
      <p
        v-if="!searchMeta || searchMeta.num_results == 0"
        class="mt-8 text-center text-gray-300"
      >
        Keine Ergebnisse
      </p>
      <div v-else class="flex flex-col gap-2">
        <p class="text-sm italic text-gray-300">{{ modelLabel }}</p>
        <item-line
          v-for="item in searchResults"
          :model="item"
          class="cursor-pointer"
          @click.prevent="selectModel(item)"
        />
      </div>
    </div>
  </div>
</template>

<style scoped></style>
