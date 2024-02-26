<script setup>
import { computed } from 'vue'

defineOptions({
  inheritAttrs: false,
})

const props = defineProps({
  modelValue: {
    type: String,
    default: '',
  },
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
  <div
    class="relative"
    :class="{ 'flex items-center': props.position == 'right' }"
  >
    <input
      type="text"
      v-bind="$attrs"
      :value="modelValue"
      @input="updateValue"
      :class="{ 'pr-24': props.position == 'right' }"
    />
    <p
      class="text-sm"
      v-if="props.modelValue"
      :class="{
        'absolute right-0 text-gray-200 pr-4': props.position == 'right',
        'text-center mt-1': props.position == 'bottom',
      }"
    >
      {{ props.modelValue.length }} / {{ props.maxlength }}
    </p>
  </div>
</template>
