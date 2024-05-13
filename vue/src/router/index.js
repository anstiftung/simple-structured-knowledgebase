import { createRouter, createWebHistory } from 'vue-router'

import routes from '@/router/routes.js'
import { useUserStore } from '@/stores/user'
import ToastPlugin from '@/plugins/toast.js'
import keycloakInstance from '@/plugins/keycloak.js'

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

  // adds refreshing the token before any page-change
  try {
    keycloakInstance.updateToken(5).then(state => {
      if (state) console.info('api token renewed.')
    })
  } catch (error) {
    ToastPlugin.error('Can`t update Authorization-Token.')
  }

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
