<script setup>
import { computed } from 'vue'

const props = defineProps({
  modelValue: String,
  maxlength: Number,
})

const emit = defineEmits(['update:modelValue'])

const updateValue = event => {
  emit('update:modelValue', event.target.value)
}

const remainingChars = computed(() => {
  return props.maxlength - props.modelValue.length
})
</script>
<template>
  <div>
    <input
      type="text"
      v-bind="$attrs"
      :value="modelValue"
      @input="updateValue"
    />
    <p class="right-0 text-sm">
      {{ props.modelValue.length }} / {{ props.maxlength }}
    </p>
  </div>
</template>
