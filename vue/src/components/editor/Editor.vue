<script setup>
import { watch } from 'vue'

import History from '@tiptap/extension-history'
import Document from '@tiptap/extension-document'
import Paragraph from '@tiptap/extension-paragraph'
import Text from '@tiptap/extension-text'
import Heading from '@tiptap/extension-heading'
import Bold from '@tiptap/extension-bold'
import Underline from '@tiptap/extension-underline'
import ListItem from '@tiptap/extension-list-item'
import OrderedList from '@tiptap/extension-ordered-list'
import BulletList from '@tiptap/extension-bullet-list'
import Italic from '@tiptap/extension-italic'
import Strike from '@tiptap/extension-strike'
import HorizontalRule from '@tiptap/extension-horizontal-rule'
import Placeholder from '@tiptap/extension-placeholder'
import Dropcursor from '@tiptap/extension-dropcursor'
import HardBreak from '@tiptap/extension-hard-break'
import { useEditor, EditorContent } from '@tiptap/vue-3'

import ImageMenu from '@/components/editor/ImageMenu.vue'

import FixedMenu from '@/components/editor/FixedMenu.vue'
import InfoBox from '@/components/editor/InfoBox.js'
import CustomImage from '@/components/editor/CustomImage.js'
import ItemLinkNode from '@/components/editor/ItemLinkNode.js'

const emit = defineEmits(['update:modelValue'])
const props = defineProps({
  modelValue: {
    type: String,
    default: '',
  },
})

const editor = useEditor({
  extensions: [
    History,
    Document,
    Paragraph,
    Text,
    Heading.configure({ levels: [2, 3, 4] }),
    Bold,
    Underline,
    BulletList,
    OrderedList,
    ListItem,
    Italic,
    Strike,
    HorizontalRule,
    CustomImage,
    Dropcursor,
    HardBreak,
    ItemLinkNode,
    InfoBox,
    Placeholder.configure({
      placeholder:
        'Bitte hier die Inhalte einpflegen, nutze dafür den Editor, auch MarkDown ist erlaubt.',
    }),
  ],
  content: props.modelValue,
  onUpdate: ({ editor }) => {
    emit('update:modelValue', editor.getHTML())
  },
  editorProps: {
    transformPastedHTML(html) {
      return html.replace(/<img.*?>/g, '')
    },
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
  <div class="relative flex flex-col h-full gap-4">
    <fixed-menu :editor="editor" v-if="editor" />
    <editor-content :editor="editor" class="prose grow" />
    <image-menu :editor="editor" />
  </div>
</template>

<style>
.tiptap {
  height: 100%;
  /* this is important because we are moving a:after elements to z=-1 */
  z-index: 0;
}
.tiptap p.is-editor-empty:first-child::before {
  content: attr(data-placeholder);
  float: left;
  color: #848484;
  pointer-events: none;
  height: 0;
}
.tiptap:focus {
  outline: none;
}

.tiptap img {
  max-width: 100%;
  height: auto;
}

.tiptap img.ProseMirror-selectednode {
  outline: 3px solid #68cef8;
}
</style>
