<script setup>
import { BubbleMenu } from '@tiptap/vue-3'
import { isActive } from '@tiptap/core'

const props = defineProps({
  editor: Object,
})

const imageStyles = {
  full: { label: 'Volle Breite', class: 'w-full' },
  left: { label: '1/6 Links', class: 'w-1/6 float-left' },
  right: { label: '1/4 Rechts', class: 'w-3/12 float-right' },
}

const setImageStyle = styleKey => {
  props.editor.commands.updateAttributes('image', {
    class: imageStyles[styleKey].class,
  })
}

const getActiveImageStyle = styleKey => {
  const classList = props.editor.getAttributes('image')['class']
  return classList === imageStyles[styleKey].class
}

const shouldShow = ({ state }) => {
  return isActive(state, 'image')
}
</script>

<template>
  <bubble-menu
    :editor="editor"
    :shouldShow="shouldShow"
    v-if="editor"
    class="flex gap-2 p-2.5 bg-gray-100 rounded-lg drop-shadow-md text-sm"
  >
    <button
      v-for="(style, styleKey) in imageStyles"
      :class="[
        'secondary-button',
        getActiveImageStyle(styleKey) ? 'bg-gray-200' : '',
      ]"
      @click="setImageStyle(styleKey)"
    >
      {{ style.label }}
    </button>
  </bubble-menu>
</template>

<style scoped></style>
