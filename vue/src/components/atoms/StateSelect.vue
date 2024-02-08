<script setup>
import { ref, computed } from 'vue'
import StateService from '@/services/StateService'

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
    states.value = data
  })
}

loadFromServer()
</script>

<template>
  <select
    class="w-full max-w-xl px-4 py-3 text-gray-800 rounded-md"
    v-model="localModel"
  >
    <option :value="null" disabled>Kein Status ausgewählt</option>
    <option v-for="state in states" :value="state">
      {{ state.title }}
    </option>
  </select>
</template>

<style scoped></style>
