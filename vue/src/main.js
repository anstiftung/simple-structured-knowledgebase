import { createApp } from 'vue'
import './style.css'
import App from './App.vue'

import VueAxios from 'vue-axios'
import axios from '@/plugins/axios.js'

const app = createApp(App)
app.use(VueAxios, axios)
app.provide('axios', app.config.globalProperties.axios)

app.mount('#app')
