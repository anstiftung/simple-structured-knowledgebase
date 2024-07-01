<script setup>
import { BubbleMenu } from '@tiptap/vue-3'
import { isActive } from '@tiptap/core'
import { ref, watch } from 'vue'

const props = defineProps({
  editor: Object,
})

const classList = ref([])
const activeState = ref(false)

// taken from: https://stackoverflow.com/a/25597485/3514523
String.prototype.splitPlus = function (sep) {
  var a = this.split(sep)
  if (a[0] == '' && a.length == 1) return []
  return a
}

const imageSizes = [
  { key: 'full', label: '100%', class: 'w-full' },
  { key: 'sixth', label: '1/6', class: 'w-1/6' },
  { key: 'fourth', label: '1/4 ', class: 'w-3/12' },
]

const imageAlignments = [
  { key: 'middle', label: 'Mitte', class: 'mx-auto' },
  { key: 'left', label: 'Links', class: 'float-left' },
  { key: 'right', label: 'Rechts', class: 'float-right' },
]

const setImageStyle = (styleClass, groupElements) => {
  // Clear all Size-Classes from classList
  classList.value = classList.value.filter(
    el => !groupElements.map(a => a.class).includes(el),
  )

  // Add StyleKey-Class to classList
  classList.value.push(styleClass)

  // Set new classString to Editor-Element
  props.editor.commands.updateAttributes('image', {
    class: classList.value.join(' '),
  })
}

watch(activeState, newState => {
  if (newState === true) {
    const classString = props.editor.getAttributes('image')['class'] ?? ''
    classList.value = classString.splitPlus(' ')
  }
})

const getActiveImageSize = styleClass => {
  return classList.value.includes(styleClass)
}

const getActiveImageAlignment = styleClass => {
  return classList.value.includes(styleClass)
}

const shouldShow = ({ state }) => {
  activeState.value = isActive(state, 'image')
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
        v-for="style in imageSizes"
        :class="[
          'secondary-button',
          'mr-1',
          'mb-1',
          getActiveImageSize(style.class) ? 'bg-gray-200' : '',
        ]"
        @click="setImageStyle(style.class, imageSizes)"
      >
        {{ style.label }}
      </button>
    </div>
    <div>
      <p class="font-bold">Ausrichtung:</p>
      <button
        v-for="style in imageAlignments"
        :class="[
          'secondary-button',
          'mr-1',
          'mb-1',
          getActiveImageAlignment(style.class) ? 'bg-gray-200' : '',
        ]"
        @click="setImageStyle(style.class, imageAlignments)"
      >
        {{ style.label }}
      </button>
    </div>
  </bubble-menu>
</template>

<style scoped></style>
