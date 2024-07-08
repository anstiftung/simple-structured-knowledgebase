import { defineStore } from 'pinia'
import { markRaw } from 'vue'

export const useModalStore = defineStore('modal', {
  state: () => ({
    isOpen: false,
    view: {},
    props: {},
    closeCallback: null,
  }),
  actions: {
    open(view, props, closeCallback) {
      this.isOpen = true
      this.props = props
      this.closeCallback = closeCallback
      // using markRaw to avoid over performance as reactive is not required
      this.view = markRaw(view)
      document.body.classList.add('overflow-hidden')
    },
    close(data = null) {
      this.isOpen = false
      this.view = {}
      this.props = {}
      this.closeCallback(data)
      document.body.classList.remove('overflow-hidden')
      // it would be wise to also clear the callback here, but this leads to issues when opening one modal from the closeCallback of another modal
    },
  },
})
