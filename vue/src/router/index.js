import { createRouter, createWebHistory } from 'vue-router'
import routes from './routes.js'
import { useUserStore } from '@/stores/user'

const router = createRouter({
  history: createWebHistory(),
  routes,
  scrollBehavior(to, from, savedPosition) {
    if (savedPosition) {
      return savedPosition
    } else {
      return { top: 0 }
    }
  },
})

router.beforeEach((to, from, next) => {
  document.title = `Cowiki | ${to.meta.title}`
  const userStore = useUserStore()
  if (to.meta.protected) {
    if (!userStore.isAuthenticated) {
      router.push({ name: 'not-authorized', replace: true })
    } else {
      next()
    }
  } else {
    next()
  }
})

export default router
