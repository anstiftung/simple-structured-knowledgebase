import { createApp } from 'vue'
import { createPinia } from 'pinia'
import './style.css'
import App from './App.vue'

import Toast, { POSITION } from 'vue-toastification'
import 'vue-toastification/dist/index.css'

import VueAxios from 'vue-axios'

import axios from '@/plugins/axios.js'
import filters from '@/plugins/filters.js'
import router from './router/index.js'

import keycloakInstance from '@/plugins/keycloak.js'

const pinia = createPinia()
const app = createApp(App)

const _keycloak = keycloakInstance

app.config.globalProperties.$filters = filters

const toastSettings = {
  position: POSITION.TOP_CENTER,
}

const renderApp = () => {
  app.use(pinia)
  app.use(VueAxios, axios)
  app.use(router)
  app.use(Toast, toastSettings)
  app.provide('axios', app.config.globalProperties.axios)
  app.provide('keycloak', _keycloak)
  app.mount('#app')
}


_keycloak
  .init({ checkLoginIframe: false })
  .then(() => {
    renderApp()
  })
