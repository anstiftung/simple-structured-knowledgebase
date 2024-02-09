import './style.css'
import 'vue-toastification/dist/index.css'

import Toast, { POSITION } from 'vue-toastification'

import App from './App.vue'
import Icon from '@/components/atoms/Icon.vue'
import VueAxios from 'vue-axios'
import axios from 'axios'
import { createApp } from 'vue'
import { createPinia } from 'pinia'
import filters from '@/plugins/filters.js'
import keycloakInstance from '@/plugins/keycloak.js'
import router from './router/index.js'
import { useUserStore } from './stores/user'

const pinia = createPinia()
const app = createApp(App)

const _keycloak = keycloakInstance

app.config.globalProperties.$filters = filters

const toastSettings = {
  position: POSITION.TOP_CENTER,
}

app.component('Icon', Icon)

app.use(pinia)
app.use(VueAxios, axios)
app.use(Toast, toastSettings)
app.provide('axios', app.config.globalProperties.axios)
app.provide('keycloak', _keycloak)

const renderApp = () => {
  const userStore = useUserStore()
  userStore.loadUserData()
  app.use(router)
  app.mount('#app')
}

_keycloak.init({ checkLoginIframe: false, onLoad: 'check-sso' }).then(() => {
  renderApp()
})
