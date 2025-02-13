<script setup>
import { ref } from 'vue'
import { onClickOutside } from '@vueuse/core'

defineOptions({
  inheritAttrs: false,
})

const props = defineProps({
  modelValue: {
    type: [String, Object],
    default: '',
  },
  helpText: String,
  maxlength: Number,
  errors: Array,
  label: String,
})

const inputId = 'id' + Math.random().toString(16).slice(2)
const helpVisible = ref(false)
const helpOverlay = ref(null)

onClickOutside(helpOverlay, () => (helpVisible.value = false))

const emit = defineEmits(['update:modelValue'])

const updateValue = event => {
  emit('update:modelValue', event.target.value)
}
</script>
<template>
  <div v-bind="$attrs" class="flex flex-col text-gray-300">
    <div class="flex text-sm">
      <label class="inline-block mb-1" :for="inputId">{{ props.label }}</label>
      <div v-if="props.helpText" class="pl-1 pr-1 relative bottom-0.5">
        <icon
          name="info"
          @click="helpVisible = !helpVisible"
          role="button"
        ></icon>
        <div
          class="triangle-before text-left absolute w-96 top-[30px] -left-3.5 z-50 p-2 text-sm text-white bg-gray-300 rounded-md shadow-md"
          v-if="helpVisible"
          ref="helpOverlay"
        >
          {{ props.helpText }}
        </div>
      </div>
      <div
        class="flex items-center gap-2 px-2 mb-1 bg-white bg-red-200 rounded-md"
        v-if="props.errors && props.errors.length"
      >
        <icon name="warning" class="text-red"></icon>
        <div class="text-red" v-for="error of props.errors" :key="error.$uid">
          {{ error.$message }}
        </div>
      </div>
    </div>
    <div class="relative grow">
      <div
        class="h-full w-full px-2 *:py-4 bg-white text-black border rounded-md outline-none has-[:focus]:ring-blue-100 has-[:focus]:ring *:w-full *:pr-24 *:outline-none"
      >
        <slot
          :inputId="inputId"
          :modelValue="modelValue"
          :updateValue="updateValue"
        ></slot>
      </div>
      <p
        v-if="props.modelValue && props.maxlength"
        class="absolute right-0 pr-4 text-sm text-gray-400 top-8"
      >
        {{ props.modelValue.length }} / {{ props.maxlength }}
      </p>
    </div>
  </div>
</template>
<style>
.triangle-before:before {
  content: '';
  position: absolute;
  top: -7px;
  left: 16px;
  width: 0;
  height: 0;
  border-left: 8px solid transparent;
  border-right: 8px solid transparent;

  border-bottom: 8px solid theme('colors.gray.300');
}
</style>
