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

const imageSizes = {
  full: { label: '100%', class: 'w-full' },
  sixth: { label: '1/6', class: 'w-1/6' },
  fourth: { label: '1/4 ', class: 'w-3/12' },
}

const imageAlignments = {
  middle: { label: 'Mitte', class: 'mx-auto' },
  left: { label: 'Links', class: 'float-left' },
  right: { label: 'Rechts', class: 'float-right' },
}

const setImageSize = styleKey => {
  props.editor.commands.updateAttributes('image', {
    class: imageSizes[styleKey].class,
  })
}

const setImageAlignment = styleKey => {
  props.editor.commands.updateAttributes('image', {
    class: imageAlignments[styleKey].class,
  })
}

const getActiveImageStyle = styleKey => {
  const classList = props.editor.getAttributes('image')['class']
  console.log(classList)
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
    <div>
      <p class="font-bold">Größe:</p>
      <button
        v-for="(style, styleKey) in imageSizes"
        :class="['secondary-button', 'mr-1', 'mb-1']"
        @click="setImageSize(styleKey)"
      >
        {{ style.label }}
      </button>
    </div>
    <div>
      <p class="font-bold">Ausrichtung:</p>
      <button
        v-for="(style, styleKey) in imageAlignments"
        :class="['secondary-button', 'mr-1', 'mb-1']"
        @click="setImageAlignment(styleKey)"
      >
        {{ style.label }}
      </button>
    </div>
  </bubble-menu>
</template>

<style scoped></style>
