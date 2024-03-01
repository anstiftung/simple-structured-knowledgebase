<script setup>
import { ref, computed, onBeforeMount } from 'vue'
import { useDebounceFn, onClickOutside } from '@vueuse/core'

import { storeToRefs } from 'pinia'
import { useRouter, useRoute } from 'vue-router'

import { useModalStore } from '@/stores/modal'
import { useUserStore } from '@/stores/user'

import SearchService from '@/services/SearchService'
import LoadingSpinner from '@/components/atoms/LoadingSpinner.vue'

const router = useRouter()
const route = useRoute()

const activeModels = ref(['articles'])

const searchResults = ref([])
const searchQuery = ref([])
const loading = ref(false)

const modal = useModalStore()
// If you need UserPermissions, you'll need the next three lines
const userStore = useUserStore()
const { hasPermission } = storeToRefs(userStore)

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
  SearchService.search(searchQuery.value, activeModels.value, false).then(
    ({ data, meta }) => {
      searchResults.value = data
      // searchMeta.value = meta
      // resultsVisible.value = true
      loading.value = false
    },
  )
}

const searchQueryUpdated = searchQuery => {
  router.replace({
    query: {
      q: encodeURI(searchQuery.value),
      m: activeModels.value,
    },
  })
  console.info('Search query updated…')
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
      class="flex items-baseline justify-between py-12 gap-x-12 width-wrapper"
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
    <div class="width-wrapper">
      <div class="pt-3 pb-2 pl-2 font-semibold">
        <h3 class="text-black">Suchergebnisse</h3>
      </div>

      <template v-if="activeModels.includes('collections')">
        <p class="pt-3 pb-2 pl-2 text-sm italic text-gray-300">Sammlungen</p>

        <p class="text-sm" v-if="searchResults.collections.length == 0">
          Keine Ergebnisse
        </p>

        <table class="w-full" v-else>
          <thead class="border-y w-full text-gray-400">
            <tr>
              <td>Titel</td>
              <td>Datum</td>
            </tr>
          </thead>
          <tbody>
            <tr v-for="collection in searchResults.collections">
              <td class="text-blue-400">{{ collection.title }}</td>
              <td>{{ collection.created_at }}</td>
            </tr>
          </tbody>
        </table>
      </template>

      <template v-if="activeModels.includes('articles')">
        <p class="pt-3 pb-2 pl-2 text-sm italic text-gray-300">Beiträge</p>

        <p class="text-sm" v-if="searchResults.articles?.length == 0">
          Keine Ergebnisse
        </p>

        <table class="w-full mb-4" v-else>
          <thead class="border-y w-full text-gray-400">
            <tr>
              <td class="px-2 py-3">Titel</td>
              <td class="px-2 py-3">Ersteller:in</td>
              <td class="px-2 py-3">Veröffentlicht</td>
              <td class="px-2 py-3">geändert</td>
              <td class="px-2 py-3">Claps</td>
              <td class="px-2 py-3">Kommentare</td>
            </tr>
          </thead>
          <tbody>
            <tr v-for="article in searchResults.articles">
              <td class="px-2 py-1 text-orange font-semibold">
                {{ article.title }}
              </td>
              <td class="px-2 py-3">{{ article.created_by.name }}</td>
              <td class="px-2 py-3">
                {{ $filters.formatedDate(article.created_at) }}
              </td>
              <td class="px-2 py-3">
                {{ $filters.formatedDate(article.updated_at) }}
              </td>
              <td class="px-2 py-3">{{ article.claps }}</td>
              <td class="px-2 py-3">{{ article.num_comments }}</td>
            </tr>
          </tbody>
        </table>
      </template>

      <template v-if="activeModels.includes('attachments')">
        <p class="pt-3 pb-2 pl-2 text-sm italic text-gray-300">Anhänge</p>

        <p class="text-sm" v-if="attachments?.length == 0">Keine Ergebnisse</p>

        <table class="w-full" v-else>
          <thead class="border-y w-full text-gray-400">
            <tr>
              <td class="px-2 py-3">Titel</td>
              <td class="px-2 py-3">Datum</td>
            </tr>
          </thead>
          <tbody>
            <tr v-for="attachment in attachments">
              <td class="px-2 py-3 text-green font-semibold">
                {{ attachment.title }}
              </td>
              <td class="px-2 py-3">
                {{ $filters.formatedDate(attachment.created_at) }}
              </td>
            </tr>
          </tbody>
        </table>
      </template>
    </div>
  </section>
</template>
