<script setup>
import { ref } from 'vue'
import { storeToRefs } from 'pinia'
import { useModalStore } from '@/stores/modal'
import { onClickOutside } from '@vueuse/core'

const modal = useModalStore()
const { isOpen, view, props } = storeToRefs(modal)
const mainModal = ref(null)
const mainModalComponent = ref(null)

document.addEventListener('keyup', function (evt) {
  if (evt.key === 'Escape' && isOpen.value) {
    shouldClose()
  }
})

onClickOutside(mainModal, () => {
  if (isOpen.value) {
    shouldClose
  }
})

const modalComponentDone = data => {
  modal.close(data)
}

const shouldClose = () => {
  // if the mainModal component exposes a shouldCloseFunction we call it before closing to prevent data loss
  if (typeof mainModalComponent.value?.shouldCloseModal === 'function') {
    mainModalComponent.value?.shouldCloseModal(() => {
      modal.close()
    })
  } else {
    modal.close()
  }
}
</script>

<template>
  <div>
    <div
      v-if="isOpen"
      class="fixed top-0 left-0 right-0 z-50 items-center justify-center overflow-x-hidden overflow-y-auto bg-gray-800/75 md:h-full md:inset-0"
    >
      <div class="relative mx-auto mt-32 width-wrapper" ref="mainModal">
        <component
          :is="view"
          v-bind="props"
          ref="mainModalComponent"
          @done="modalComponentDone"
        ></component>

        <div class="absolute right-0 text-3xl text-white -top-12">
          <button class="" title="Schließen" @click="shouldClose()">✕</button>
        </div>
      </div>
    </div>
  </div>
</template>
