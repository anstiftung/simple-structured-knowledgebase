<script setup>
import { ref, computed } from 'vue'
import { storeToRefs } from 'pinia'

import StateService from '@/services/StateService'
import { useUserStore } from '@/stores/user'

const userStore = useUserStore()
const { hasPermission } = storeToRefs(userStore)

const selectDisabled = ref(false)

const emit = defineEmits(['update:modelValue'])
const props = defineProps({
  modelValue: {
    type: Object,
    default: () => ({}),
  },
})

// borrowed from https://dev.to/blindkai/objects-and-v-model-in-vue3-1l9h
const localModel = computed({
  get: () => props.modelValue,
  set: value => emit('update:modelValue', value),
})

const states = ref([])

const loadFromServer = () => {
  StateService.getStates().then(data => {
    if (hasPermission.value('publish articles')) {
      states.value = data
    } else {
      if (props.modelValue.key == 'publish') {
        // case one: is published -> not allowed to do anything
        selectDisabled.value = true
        states.value = data
      } else {
        // case two: is not published -> allowed to set draft/review
        states.value = data.filter(s => s.key != 'publish')
      }
    }
  })
}

loadFromServer()
</script>

<template>
  <select
    class="w-full max-w-xl px-4 py-3 text-gray-800 rounded-md"
    v-model="localModel"
    :disabled="selectDisabled"
  >
    <option :value="null" disabled>Kein Status ausgewählt</option>
    <option v-for="state in states" :value="state">
      {{ state.title }}
    </option>
  </select>
</template>

<style scoped></style>
