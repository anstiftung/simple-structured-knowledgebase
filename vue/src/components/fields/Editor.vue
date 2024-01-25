<script setup>
import { watch } from 'vue'
import { useEditor, EditorContent } from '@tiptap/vue-3'
import Underline from '@tiptap/extension-underline'
import Link from '@tiptap/extension-link'
import StarterKit from '@tiptap/starter-kit'
import Placeholder from '@tiptap/extension-placeholder'
import FloatingMenu from '@/components/fields/FloatingMenu.vue'
import FixedMenu from '@/components/fields/FixedMenu.vue'

const emit = defineEmits(['update:modelValue'])
const props = defineProps({
  modelValue: {
    type: String,
    default: '',
  },
})

const ModelLink = Link.extend({
  addAttributes() {
    return {
      'data-type': {
        default: null,
      },
      href: {
        default: null,
      },
    }
  },
})

const editor = useEditor({
  extensions: [
    StarterKit.configure({
      headings: { levels: [1, 2, 3, 4] },
    }),
    Underline,
    ModelLink.configure({
      openOnClick: false,
      HTMLAttributes: {
        target: null,
      },
    }),
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
    <fixed-menu :editor="editor" v-if="editor" />

    <editor-content :editor="editor" class="prose grow" />

    <floating-menu :editor="editor" />
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
.ProseMirror:focus {
  outline: none;
}
</style>
