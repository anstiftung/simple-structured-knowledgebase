import Landing from '@/views/Landing.vue'
import Recipe from '@/views/Recipe.vue'
import Recipes from '@/views/Recipes.vue'
import Profile from '@/views/Profile.vue'
import NotAuthorized from '@/views/NotAuthorized.vue'

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
    path: '/profile',
    component: Profile,
    name: 'profile',
    meta: {
        title: 'Mein Profil',
        protected: true,
    }
  },
  {
    path: '/not-authorized',
    component: NotAuthorized,
    name: 'not-authorized',
    meta: {
        title: 'Zugriff verweigert'
    }
  }
]
