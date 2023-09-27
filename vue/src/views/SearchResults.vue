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
        <template v-if="searchMeta && searchMeta.numResults > 0"
          >{{ searchMeta.numResults }}
          {{ searchMeta.numResults > 1 ? 'Ergebnisse' : 'Ergebnis' }}</template
        >
        <template v-else>Keine Ergebnisse</template>
      </h2>
      <template v-if="searchMeta.numCollections">
        <h3>
          {{ searchMeta.numCollections }}
          {{ searchMeta.numCollections > 1 ? 'Sammlungen' : 'Sammlung' }}
        </h3>
        <div v-for="collection in searchResults.collections">
          {{ collection.title }}
        </div>
      </template>
      <template v-if="searchMeta.numRecipes">
        <h3>
          {{ searchMeta.numRecipes }}
          {{ searchMeta.numRecipes > 1 ? 'Rezepte' : 'Rezept' }}
        </h3>
        <div v-for="recipe in searchResults.recipes">
          {{ recipe.title }}
        </div>
      </template>
      <template v-if="searchMeta.numIngredients">
        <h3>
          {{ searchMeta.numIngredients }}
          {{ searchMeta.numIngredients > 1 ? 'Zuataten' : 'Zutat' }}
        </h3>
        <div v-for="ingredient in searchResults.ingredients">
          {{ ingredient.title }}
        </div>
      </template>
    </section>
  </div>
</template>
