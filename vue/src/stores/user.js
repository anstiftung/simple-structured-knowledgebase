import axios from 'axios'
import { defineStore } from 'pinia'
// import { inject } from 'vue'
import keycloakInstance from '@/plugins/keycloak'
import { useLocalStorage } from '@vueuse/core'

export const useUserStore = defineStore('user', {
  state: () => ({
    name: useLocalStorage('auth-name', null),
    email: useLocalStorage('auth-email', null),
    id: useLocalStorage('auth-id', null),
    permissions: useLocalStorage('auth-permissions', []),
  }),
  getters: {
    hasPermission: state => {
      return p => {
        return state.permissions.includes(p)
      }
    },
  },
  actions: {
    async loadUserData() {
      // const $keycloak = inject('keycloak')
      await axios
        .get('api/user-credentials', {
          headers: {
            Authorization: 'Bearer ' + keycloakInstance.token,
          },
        })
        .then(response => {
          this.name = response.data.data.name
          this.email = response.data.data.email
          this.id = response.data.data.id
          this.permissions = response.data.data.permissions
        })
        .catch(err => {
          console.log(err)
        })
    },
    logout() {
      this.name = null
      this.email = null
      this.id = null
      this.permissions = []
    },
  },
})
