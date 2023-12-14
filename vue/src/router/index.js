import { createRouter, createWebHistory } from 'vue-router'
import routes from './routes.js'
import { inject } from 'vue'

const router = createRouter({
  history: createWebHistory(),
  routes,
})

router.beforeEach((to, from, next) => {
    const $keycloak = inject('keycloak')
    if (to.meta.protected) {
      // Get the actual url of the app, it's needed for Keycloak
      console.log('protected...')
      const basePath = window.location.origin.toString()
      if (!$keycloak.authenticated) {
        // The page is protected and the user is not authenticated. Force a login.
        console.log('not authenticated')

        $keycloak.login({onLoad: 'login-required', checkLoginIframe: false}).then(() => {
            console.log('redirect to next....')
            next()
        }).catch( err => {
            console.error(err)
            next({ name: 'not-authorized' })
        })
      } else {
        console.log('not authenticated')
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
