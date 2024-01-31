<script setup>
import { BubbleMenu } from '@tiptap/vue-3'
import Icon from '@/components/Icon.vue'

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
      <icon name="bold" />
    </button>
    <button
      @click="editor.chain().focus().toggleItalic().run()"
      :class="[
        [editor.isActive('italic') ? 'bg-gray-200' : ''],
        'secondary-button-square secondary-button',
      ]"
    >
      <icon name="italic" />
    </button>
    <button
      @click="editor.chain().focus().toggleUnderline().run()"
      :class="[
        [editor.isActive('underline') ? 'bg-gray-200' : ''],
        'secondary-button-square secondary-button',
      ]"
    >
      <icon name="underline" />
    </button>
    <button
      @click="editor.chain().focus().toggleStrike().run()"
      :class="[
        [editor.isActive('strike') ? 'bg-gray-200' : ''],
        'secondary-button-square secondary-button',
      ]"
    >
      <icon name="strikethrough" />
    </button>
  </bubble-menu>
</template>

<style scoped></style>
