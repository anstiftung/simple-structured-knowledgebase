const Recipe = () => import( '@/views/Recipe.vue')
const Recipes = () => import( '@/views/Recipes.vue')

export default [
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
]
