import { defineStore } from 'pinia'
import { markRaw } from 'vue'

export const useModalStore = defineStore('modal', {
  state: () => ({
    isOpen: false,
    view: {},
    closeCallback: null,
  }),
  actions: {
    open(view, closeCallback) {
      this.isOpen = true
      this.closeCallback = closeCallback
      // using markRaw to avoid over performance as reactive is not required
      this.view = markRaw(view)
    },
    close() {
      this.isOpen = false
      this.view = {}
      this.closeCallback()
      this.closeCallback = null
    },
  },
})
