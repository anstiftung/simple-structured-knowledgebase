import { createRouter, createWebHistory } from 'vue-router'

import routes from '@/router/routes.js'
import { useUserStore } from '@/stores/user'
import refreshToken from '@/plugins/keycloak-token-refresh.js'

const router = createRouter({
  history: createWebHistory(),
  routes,
  scrollBehavior(to, from, savedPosition) {
    if (savedPosition) {
      return savedPosition
    } else if (to.hash) {
      return { el: to.hash }
    } else {
      return { top: 0 }
    }
  },
})

router.beforeEach((to, from, next) => {
  document.title = `Cowiki | ${to.meta.title}`
  const userStore = useUserStore()

  refreshToken()

  if (
    to.hash.startsWith('#error=login_required') ||
    to.hash.startsWith('#state=')
  ) {
    to.hash = null
    return next(to)
  }

  if (to.meta.protected && !userStore.isAuthenticated) {
    router.push({ name: 'not-authorized', replace: true })
  }

  next()
})

export default router
