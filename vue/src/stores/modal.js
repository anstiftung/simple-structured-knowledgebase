import { defineStore } from 'pinia'
import { markRaw } from 'vue'

export const useModalStore = defineStore('modal', {
  state: () => ({
    isOpen: false,
    view: {},
    actions: [],
  }),
  actions: {
    open(view, actions) {
      this.isOpen = true
      this.actions = actions
      // using markRaw to avoid over performance as reactive is not required
      this.view = markRaw(view)
    },
    close() {
      this.isOpen = false
      this.view = {}
      this.actions = []
    },
  },
})
