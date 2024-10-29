import axios from 'axios'
import { defineStore } from 'pinia'
import { useLocalStorage } from '@vueuse/core'

export const useUserStore = defineStore('user', {
  state: () => ({
    name: useLocalStorage('auth-name', null),
    email: useLocalStorage('auth-email', null),
    id: useLocalStorage('auth-id', null),
    permissions: useLocalStorage('auth-permissions', []),
    token: useLocalStorage('auth-token', null),
  }),
  getters: {
    hasPermission: state => {
      return p => {
        return state.permissions.includes(p)
      }
    },
    isAuthenticated: state => {
      return state.name != null && state.id != null && state.email != null
    },
    getUser: state => {
      return {
        id: state.id,
        name: state.name,
      }
    },
    getToken: state => {
      return {
        token: state.token,
      }
    },
  },
  actions: {
    async loadUserData(token) {
      await axios
        .get('/api/user-credentials', {
          headers: {
            Authorization: 'Bearer ' + token,
          },
        })
        .then(response => {
          this.name = response.data.data.name
          this.email = response.data.data.email
          this.id = response.data.data.id
          this.permissions = response.data.data.permissions
        })
        .catch(err => {
          console.error(err)
        })
    },
    deleteUserData() {
      this.token = null
      this.name = null
      this.email = null
      this.id = null
      this.permissions = []
    },
    setToken(token) {
      this.token = token
    },
  },
})
