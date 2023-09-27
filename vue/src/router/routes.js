import Landing from '@/views/Landing.vue'
import Recipe from '@/views/Recipe.vue'
import Recipes from '@/views/Recipes.vue'
import SearchResults from '@/views/SearchResults.vue'

export const routes = [
  {
    path: '/',
    component: Landing,
    name: 'landing',
    meta: {
      title: 'Startseite',
    },
  },
  {
    path: '/rezepte',
    component: Recipes,
    name: 'recipes',
    meta: {
      title: 'Rezepte',
    },
  },
  {
    path: '/rezepte/:slug',
    component: Recipe,
    name: 'recipe',
    meta: {
      title: 'Rezept',
    },
  },
  {
    path: '/suche/:query?',
    component: SearchResults,
    name: 'search',
    meta: {
      title: 'Suche',
    },
  },
]
