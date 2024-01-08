<script setup>
import { reactive } from 'vue'
import { storeToRefs } from 'pinia'
import { useModalStore } from '@/stores/modal'

const modal = useModalStore()
const model = reactive({})
const { isOpen, view, actions } = storeToRefs(modal)

document.addEventListener('keyup', function (evt) {
  if (evt.key === 'Escape') {
    modal.close()
  }
})
</script>

<template>
  <div>
    <div
      v-if="isOpen"
      class="fixed left-0 right-0 z-50 items-center justify-center overflow-x-hidden overflow-y-auto bg-gray-800/75 md:h-full top-4 md:inset-0"
    >
      <div class="relative mx-auto bg-white width-wrapper">
        <component :is="view" v-model="model"></component>

        <div class="flex justify-end gap-4">
          <label class="" @click="modal.close()">✕</label>
          <button
            v-for="action in actions"
            class="btn"
            @click="action.callback(model)"
          >
            {{ action.label }}
          </button>
        </div>
      </div>
    </div>
  </div>
</template>
