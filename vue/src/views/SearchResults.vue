<script setup>
import { ref, onMounted, watch } from 'vue'
import { useRoute } from 'vue-router'
import SearchService from '@/services/SearchService'

import SearchForm from '@/components/SearchForm.vue'

const route = useRoute()
const searchResults = ref([])
const searchMeta = ref([])

onMounted(() => {
  loadFromServer()
})

watch(
  () => route.params.query,
  () => {
    loadFromServer()
  },
)

const loadFromServer = () => {
  if (route.params.query) {
    SearchService.search(route.params.query).then(({ data, meta }) => {
      searchResults.value = data
      searchMeta.value = meta
    })
  }
}
</script>

<template>
  <div>
    <section class="bg-gray-200">
      <div class="py-12 width-small-wrapper">
        <h4 class="text-sm text-white uppercase">Suche</h4>
        <div class="mt-2">
          <search-form />
        </div>
      </div>
    </section>
    <section v-if="searchResults" class="my-8 width-wrapper">
      <h2>
        <template v-if="searchMeta && searchMeta.num_results > 0"
          >{{ searchMeta.num_results }}
          {{
            searchMeta.num_results > 1 ? 'Ergebnisse:' : 'Ergebnis:'
          }}</template
        >
        <template v-else>Keine Ergebnisse</template>
      </h2>

      <template v-if="searchMeta.num_articles">
        <h3>
          {{ searchMeta.num_articles }}
          Artikel
        </h3>
        <div v-for="article in searchResults.articles">
          {{ article.title }}
        </div>
      </template>
      <template v-if="searchMeta.num_attached_urls">
        <h3>
          {{ searchMeta.num_attached_urls }}
          {{ searchMeta.num_attached_urls > 1 ? 'Zutat-URLs' : 'Zutat-URL' }}
        </h3>
        <div v-for="url in searchResults.attached_urls">
          {{ url.title }}
        </div>
      </template>
      <template v-if="searchMeta.num_attached_files">
        <h3>
          {{ searchMeta.num_attached_files }}
          {{ searchMeta.num_attached_files > 1 ? 'Zutat-Files' : 'Zutat-File' }}
        </h3>
        <div v-for="file in searchResults.attached_files">
          {{ file.title }}
        </div>
      </template>
    </section>
  </div>
</template>
