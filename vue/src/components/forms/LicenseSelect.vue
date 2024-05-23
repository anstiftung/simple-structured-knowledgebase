<script setup>
import { ref, computed, onMounted } from 'vue'
import LicenseService from '@/services/LicenseService'

const emit = defineEmits(['update:modelValue'])
const props = defineProps({
  modelValue: {
    type: Object,
    default: () => ({}),
  },
  defaultLicense: {
    type: String,
    default: '',
  },
})

onMounted(() => {})
// borrowed from https://dev.to/blindkai/objects-and-v-model-in-vue3-1l9h
const localModel = computed({
  get: () => props.modelValue,
  set: value => emit('update:modelValue', value),
})

const licenses = ref([])

const loadFromServer = () => {
  LicenseService.getLicenses().then(data => {
    licenses.value = data

    // the value is empty and a defaultLicense is provided
    if (!localModel.value && props.defaultLicense) {
      const license = licenses.value.find(e => e.title === props.defaultLicense)
      if (license) {
        localModel.value = license
      }
    }
  })
}

loadFromServer()
</script>

<template>
  <select
    class="w-full max-w-xl px-4 py-3 text-gray-800 bg-white rounded-md"
    v-model="localModel"
  >
    <option v-for="license in licenses" :value="license">
      {{ license.title }}
    </option>
  </select>
</template>

<style scoped></style>
