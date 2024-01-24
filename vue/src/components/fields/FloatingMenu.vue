<script setup>
import { BubbleMenu } from '@tiptap/vue-3'

const props = defineProps({
  editor: Object,
})
</script>

<template>
  <bubble-menu
    :editor="editor"
    :tippy-options="{ duration: 100 }"
    v-if="editor"
    class="flex gap-2 p-2.5 bg-gray-100 rounded-lg drop-shadow-xl text-sm"
  >
    <button
      @click="editor.chain().focus().toggleBold().run()"
      :class="[
        [editor.isActive('bold') ? 'bg-gray-200' : ''],
        'secondary-button-square secondary-button',
      ]"
    >
      <b>B</b>
    </button>
    <button
      @click="editor.chain().focus().toggleItalic().run()"
      :class="[
        [editor.isActive('italic') ? 'bg-gray-200' : ''],
        'secondary-button-square secondary-button',
      ]"
    >
      <italic>K</italic>
    </button>
    <button
      @click="editor.chain().focus().toggleUnderline().run()"
      :class="[
        [editor.isActive('underline') ? 'bg-gray-200' : ''],
        'secondary-button-square secondary-button',
      ]"
    >
      <span class="underline">U</span>
    </button>
    <button
      @click="editor.chain().focus().toggleStrike().run()"
      :class="[
        [editor.isActive('strike') ? 'bg-gray-200' : ''],
        'secondary-button-square secondary-button',
      ]"
    >
      <strike>D</strike>
    </button>
  </bubble-menu>
</template>

<style scoped></style>
