import { createRouter, createWebHistory } from 'vue-router'

import { inject } from 'vue'
import routes from './routes.js'

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
  const $keycloak = inject('keycloak')
  if (to.meta.protected) {
    // Get the actual url of the app, it's needed for Keycloak
    const basePath = window.location.origin.toString()
    if (!$keycloak.authenticated) {
      // The page is protected and the user is not authenticated. Force a login.

      $keycloak
        .login({ redirectUri: basePath + to.path })
        .then(() => {
          next()
        })
        .catch(err => {
          console.error(err)
          next({ name: 'not-authorized' })
        })
    } else {
      next()
    }
    // $keycloak.updateToken(70)
    //   .then(() => {
    //     next()
    // })
    // .catch(err => {
    //    console.error(err)
    // })
  } else {
    // This page did not require authentication
    next()
  }
})

export default router
