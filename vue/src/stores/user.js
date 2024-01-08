import axios from 'axios'
import { inject } from 'vue'
import { defineStore } from 'pinia'

export const useUserStore = defineStore('user',{
    state: () => ({
        name: null,
        email: null,
        id: null,
        permissions: []
    }),
    getters: {
        hasPermission: (state) => {
          return (p) => {
            return state.permissions.includes(p)
          }
        },
      },
    actions: {
        async initUser() {
            const $keycloak = inject('keycloak')
            await axios.get('api/dashboard', {
                headers: {
                    'Authorization': 'Bearer ' + $keycloak.token,
                }
            }).then((response) => {
                this.name = response.data.data.name
                this.email = response.data.data.email
                this.id = response.data.data.id
                this.permissions = response.data.data.permissions
            }).catch(err => {
                console.log(err)
            })
        },
        logout() {
            this.name = null
            this.email = null
            this.id = null
            this.permissions = []
        }
    }
})
