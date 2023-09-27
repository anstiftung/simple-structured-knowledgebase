import { createApp } from 'vue'
import './style.css'
import App from './App.vue'

import VueAxios from 'vue-axios'

import axios from '@/plugins/axios.js'
import filters from '@/plugins/filters.js'
import router from './router/index.js'

const app = createApp(App)

app.config.globalProperties.$filters = filters

const renderApp = () => {
  app.use(VueAxios, axios)
  app.use(router)
  app.provide('axios', app.config.globalProperties.axios)
  app.mount('#app')
}

renderApp()
