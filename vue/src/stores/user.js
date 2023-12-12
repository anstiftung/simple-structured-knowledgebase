import { useLocalStorage } from "@vueuse/core"
import { defineStore } from 'pinia'

export const useUserStore = defineStore('user', {
    state: () => ({
        token: useLocalStorage('keycloakToken', null)
    }),
    getters: {
      hasToken: (state) => { return state.token !== null ? true : false },
    },
    actions: {
      setToken(token) {
        this.token = token
        window.localStorage.setItem('keycloakToken', token)
        console.log('set token...' + token)
      },
      removeToken() {
        this.token = undefined
        window.localStorage.removeItem('keycloakToken')
        console.log('remove token...')
      },
    },
})
