<script setup>
import { reactive } from 'vue'
import { storeToRefs } from 'pinia'
import { useModalStore } from '@/stores/modal'

const modal = useModalStore()
const model = reactive({})
const { isOpen, view } = storeToRefs(modal)

document.addEventListener('keyup', function (evt) {
  if (evt.key === 'Escape') {
    modal.close()
  }
})

const onClickOutside = () => {
  if (isOpen.value) {
    modal.close()
  }
}
</script>

<template>
  <div>
    <div
      v-if="isOpen"
      class="fixed top-0 left-0 right-0 z-50 items-center justify-center overflow-x-hidden overflow-y-auto bg-gray-800/75 md:h-full md:inset-0"
      @click.self="onClickOutside"
    >
      <div class="relative mx-auto mt-32 width-wrapper">
        <component :is="view" v-model="model"></component>

        <div class="absolute right-0 text-3xl text-white -top-12">
          <button class="" title="Schließen" @click="modal.close()">✕</button>
        </div>
      </div>
    </div>
  </div>
</template>
