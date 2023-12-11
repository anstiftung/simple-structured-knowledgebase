import { defineStore } from 'pinia'

export const useUserStore = defineStore('user', {
    state: () => ({
        token: window.localStorage.getItem('keycloakToken') ?? undefined
    }),
    getters: {
      hasToken: (state) => { state.token !== undefined ? true : false },
    },
    actions: {
      setToken(token) {
        this.token = token
        window.localStorage.setItem('keycloakToken', token)
      },
      removeToken() {
        this.token = undefined
        window.localStorage.removeItem('keycloakToken')
      },
    },
})
