<script setup>
import { ref, computed } from 'vue'
import UserService from '@/services/UserService'

const emit = defineEmits(['update:modelValue'])
const props = defineProps({
  modelValue: {
    type: Object,
    default: () => ({}),
  },
  onlyShowEditors: {
    type: Boolean,
    default: false,
  },
})

// borrowed from https://dev.to/blindkai/objects-and-v-model-in-vue3-1l9h
const localModel = computed({
  get: () => props.modelValue,
  set: value => emit('update:modelValue', value),
})

const users = ref([])

const loadFromServer = () => {
  UserService.getUsers(props.onlyShowEditors).then(data => {
    users.value = data
  })
}

loadFromServer()
</script>

<template>
  <select
    class="w-full max-w-xl px-4 py-3 text-gray-800 rounded-md"
    v-model="localModel"
  >
    <option :value="null" disabled>Keine Nutzer:in ausgewählt</option>
    <option v-for="user in users" :value="user">
      {{ user.name }}
    </option>
  </select>
</template>

<style scoped></style>
