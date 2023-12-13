import { createRouter, createWebHistory } from 'vue-router'
import routes from './routes.js'
import KeyCloakService from '@/plugins/keycloak.js'

const router = createRouter({
  history: createWebHistory(),
  routes,
})

const checkAuth = () => {
    // 1. Check if token in local storage
    // 2. If no token try to login
    KeyCloakService.CallLogin() // async, wait for response, do it better nexttime ;)
    if( !window.localStorage.getItem('keycloakToken') ) return false;
    if( window.localStorage.getItem('keycloakToken') == 'undefined') return false;
    console.log('[Router] logged in…');
    return true
}

router.beforeEach((to, from) => {
  document.title = `CoWiki – ${to.meta.title ?? ''}`

  // If Route is protected check for jwt-token
  if(to.meta.protected == true && checkAuth() == false && to.name !== 'not-authorized') {
    return { name: 'not-authorized' }
  }
})

export default router
