import './style.css'
import 'vue-toastification/dist/index.css'

import Toast, { POSITION } from 'vue-toastification'

import App from './App.vue'
import Icon from '@/components/atoms/Icon.vue'
import ItemLink from '@/components/atoms/ItemLink.vue'
import VueAxios from 'vue-axios'
import axios from 'axios'
import { createApp } from 'vue'
import { createPinia } from 'pinia'
import filters from '@/plugins/filters.js'
import keycloakInstance from '@/plugins/keycloak.js'
import router from './router/index.js'

const pinia = createPinia()
const app = createApp(App)

const _keycloak = keycloakInstance

app.config.globalProperties.$filters = filters

const toastSettings = {
  position: POSITION.TOP_CENTER,
}

app.component('ItemLink', ItemLink)
app.component('Icon', Icon)

const renderApp = () => {
  app.use(pinia)
  app.use(VueAxios, axios)
  app.use(router)
  app.use(Toast, toastSettings)
  app.provide('axios', app.config.globalProperties.axios)
  app.provide('keycloak', _keycloak)
  app.mount('#app')
}

_keycloak.init({ checkLoginIframe: false }).then(() => {
  renderApp()
})
