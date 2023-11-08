<script setup>
import { ref } from 'vue'
import RecipeService from '@/services/RecipeService'

const recipes = ref([])
const recipesMeta = ref()

const loadFromServer = page => {
  RecipeService.getRecipes(page).then(({ data, meta }) => {
    recipes.value = recipes.value.concat(data)
    recipesMeta.value = meta
  })
}

loadFromServer()
</script>

<template>
  <section class="bg-white">
    <!-- todo: generalize -->
    <div class="py-12 width-wrapper">
      <h2 class="mb-4 text-2xl font-bold">Alle Rezepte</h2>
      <div class="grid grid-cols-3 gap-4">
        <router-link
          :to="{
            name: 'recipe',
            params: { slug: recipe.slug },
          }"
          v-for="recipe in recipes"
          >{{ recipe.title }}</router-link
        >
      </div>
      <template
        v-if="recipesMeta && recipesMeta.current_page < recipesMeta.last_page"
      >
        <a
          class="block mt-8 text-blue"
          @click.prevent="loadFromServer(recipesMeta.current_page + 1)"
          >Weitere Daten laden</a
        >
      </template>
    </div>
  </section>
</template>
