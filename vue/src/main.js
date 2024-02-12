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

app.config.globalProperties.$filters = filters

const toastSettings = {
  position: POSITION.TOP_CENTER,
}

app.component('Icon', Icon)

app.provide('axios', app.config.globalProperties.axios)
app.provide('keycloak', keycloakInstance)

app.use(pinia)
app.use(VueAxios, axios)
app.use(Toast, toastSettings)

keycloakInstance
  .init({ checkLoginIframe: false, onLoad: 'check-sso' })
  .then(auth => {
    const userStore = useUserStore()
    if (!auth) {
      userStore.deleteUserData()
    }

    // It's important add router AFTER eventually userStore is deleted!
    app.use(router)
    app.mount('#app')
  })
