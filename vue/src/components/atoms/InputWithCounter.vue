<script setup>
import { computed } from 'vue'

const props = defineProps({
  modelValue: String,
  maxlength: Number,
  position: {
    type: String,
    default: 'right',
    validator(value, props) {
      return ['bottom', 'right'].includes(value)
    },
  },
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
  <div class="relative">
    <input
      type="text"
      v-bind="$attrs"
      :value="modelValue"
      @input="updateValue"
    />
    <p
      class="text-sm"
      :class="{
        'absolute right-0': props.position == right,
        'text-center mt-1': props.position == bottom,
      }"
    >
      {{ props.modelValue.length }} / {{ props.maxlength }}
    </p>
  </div>
</template>
