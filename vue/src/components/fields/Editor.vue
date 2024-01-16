<script setup>
import { watch } from 'vue'
import { useEditor, EditorContent } from '@tiptap/vue-3'

import StarterKit from '@tiptap/starter-kit'
import Placeholder from '@tiptap/extension-placeholder'

const emit = defineEmits(['update:modelValue'])
const props = defineProps({
  modelValue: {
    type: String,
    default: '',
  },
})

const editor = useEditor({
  extensions: [
    StarterKit,
    Placeholder.configure({
      placeholder: 'Inhalte einpflegen',
    }),
  ],
  content: props.modelValue,
  onUpdate: ({ editor }) => {
    emit('update:modelValue', editor.getHTML())
  },
})

watch(
  () => props.modelValue,
  newValue => {
    const isSame = editor.value?.getHTML() === newValue
    if (isSame) {
      return
    }

    editor.value?.commands.setContent(newValue, false)
  },
)
</script>

<template>
  <div class="flex flex-col h-full gap-4">
    <div class="flex gap-2" v-if="editor">
      <button
        class="secondary-button"
        @click="editor.chain().focus().toggleBold().run()"
      >
        Bold {{ editor.isActive('bold') ? '*' : '' }}
      </button>
      <button
        class="secondary-button"
        @click="editor.chain().focus().toggleItalic().run()"
      >
        Italic {{ editor.isActive('italic') ? '*' : '' }}
      </button>
      <button
        class="secondary-button"
        @click="editor.commands.toggleBulletList()"
      >
        List {{ editor.isActive('bulletList') ? '*' : '' }}
      </button>
      <button class="secondary-button" @click="editor.commands.undo()">
        Undo
      </button>
      <button class="secondary-button" @click="editor.commands.redo()">
        Redo
      </button>
    </div>
    <editor-content :editor="editor" class="grow" />
  </div>
</template>

<style>
.tiptap {
  height: 100%;
}
.tiptap p.is-editor-empty:first-child::before {
  content: attr(data-placeholder);
  float: left;
  color: #848484;
  pointer-events: none;
  height: 0;
}

.tiptap ul {
  list-style: disc;
}
</style>
