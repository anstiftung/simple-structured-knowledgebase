<script setup>
import { ref, computed, onBeforeMount, watch } from 'vue'
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
const searchQuery = ref('')
const sortBy = ref('created_at')
const sortOrder = ref('asc')
const loading = ref(false)

const attachments = computed(() => {
  return AttachmentService.combineAttachments(
    searchResults.value.attached_urls ?? [],
    searchResults.value.attached_files ?? [],
    sortBy.value,
    sortOrder.value,
  )
})

const onQueryInput = useDebounceFn(() => {
  updateRouteParams()
}, 250)

const querySearch = () => {
  loading.value = true
  SearchService.search(
    searchQuery.value,
    activeModels.value,
    false,
    creatorId.value,
    true, // include trashed
    sortBy.value,
    sortOrder.value,
  ).then(({ data, meta }) => {
    searchResults.value = data
    // searchMeta.value = meta
    // resultsVisible.value = true
    loading.value = false
  })
}

const updateRouteParams = () => {
  router.replace({
    query: {
      q: searchQuery.value ? encodeURI(searchQuery.value) : '',
      m: activeModels.value,
      o: creatorId.value,
      sortBy: sortBy.value,
      sortOrder: sortOrder.value,
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

  if (route.query.sortBy) {
    sortBy.value = route.query.sortBy
  }

  if (route.query.sortOrder) {
    sortOrder.value = route.query.sortOrder
  }

  querySearch()
})

const switchModel = model => {
  activeModels.value = []
  activeModels.value.push(model)
  sortBy.value = 'created_at'
  sortOrder.value = 'asc'
  updateRouteParams()
}

const changeSort = data => {
  sortBy.value = data.sortBy
  sortOrder.value = data.sortOrder
  updateRouteParams()
}

watch(
  route,
  () => {
    querySearch()
  },
  { immediate: false, deep: true },
)
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
            :sortBy="sortBy"
            :sortOrder="sortOrder"
            @sortChanged="changeSort"
          />
        </template>

        <template v-if="activeModels.includes('articles')">
          <article-table
            v-model="searchResults.articles"
            v-if="searchResults.articles"
            :sortBy="sortBy"
            :sortOrder="sortOrder"
            @sortChanged="changeSort"
          />
        </template>

        <template v-if="activeModels.includes('attachments')">
          <attachment-table
            v-model="attachments"
            :sortBy="sortBy"
            :sortOrder="sortOrder"
            v-if="attachments"
            @sortChanged="changeSort"
          />
        </template>
      </template>
    </div>
  </section>
</template>
