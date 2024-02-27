<script setup>
import { ref, computed, onMounted } from 'vue'
import { useModalStore } from '@/stores/modal'

import ModelSelector from '@/components/atoms/ModelSelector.vue'
import {
  Listbox,
  ListboxButton,
  ListboxOptions,
  ListboxOption,
} from '@headlessui/vue'

const modal = useModalStore()

const props = defineProps({
  editor: Object,
})

/* dropdown with differenct node Styles */
const selectedStyle = ref({ key: 'p', label: 'Absatz' })
const styleOptions = ref([
  { key: 'p', label: 'Absatz' },
  { key: 'h2', label: 'Headline 1', level: 2 },
  { key: 'h3', label: 'Headline 2', level: 3 },
  { key: 'h4', label: 'Headline 3', level: 4 },
])

const onStyleInput = () => {
  if (selectedStyle.value.key === 'p') {
    props.editor.chain().focus().clearNodes().run()
  } else {
    props.editor
      .chain()
      .focus()
      .setHeading({ level: selectedStyle.value.level })
      .run()
  }
}

/* dropdown with different link options */
const selectedLink = ref(null)
const linkOptions = ref([
  { key: 'collections', label: 'Sammlung' },
  { key: 'articles', label: 'Beitrag' },
  { key: 'attachments', label: 'Anhang' },
])

const onLinkInput = () => {
  toggleLinkSelection(selectedLink.value.key)
}

/* dropdown with differenct Infoboxes */
const selectedInfoBox = ref(null)
const infoBoxOptions = ref([
  { key: 'warning', label: 'Hinweis' },
  { key: 'danger', label: 'Gefahrenhinweis' },
  { key: 'question', label: 'Fragestellung' },
])

const onInfoBoxInput = () => {
  props.editor.commands.setNode('infoBox', {
    'data-type': selectedInfoBox.value.key,
  })
}
/* allows toggling links to different model types */
const toggleLinkSelection = type => {
  // get current text selection
  const { view, state } = props.editor
  const { from, to } = view.state.selection
  let text = state.doc.textBetween(from, to, '')

  // remove itemLink
  if (props.editor.isActive('itemLink')) {
    props.editor.commands.deleteNode('itemLink')
    props.editor.commands.insertContent(text)
    return
  }

  // add itemLink
  modal.open(ModelSelector, { modelType: type }, selection => {
    if (selection) {
      let attributes = {
        href: selection.url,
        'data-type': selection.type,
        'data-id': selection.id,
      }
      // lets open links to attachments in a new tab
      if (selection.type == 'AttachedUrl' || selection.type == 'AttachedFile') {
        attributes['target'] = '_blank'
      }

      // fallback for empty text selection: title of the model
      if (!text) {
        text = selection.title
      }

      // insert itemLink with attributes and content
      props.editor.commands.insertContent({
        type: 'itemLink',
        attrs: attributes,
        content: [
          {
            type: 'text',
            text: text,
          },
        ],
      })
    }
  })
}

/* allows inserting attachments as inline images */
const insertAttachmentAsImage = () => {
  modal.open(ModelSelector, { modelType: 'images' }, selection => {
    if (selection) {
      let attributes = {
        src: selection.serve_url,
        alt: selection.title,
        title: '(c) ' + selection.source,
      }
      props.editor.commands.setImage(attributes)
    }
  })
}

/* selectionUpdate watcher for currently active nodes */
onMounted(() => {
  props.editor.on('selectionUpdate', ({ editor }) => {
    // updates selectedStyle value
    if (editor.isActive('paragraph')) {
      selectedStyle.value = styleOptions.value.find(o => o.key == 'p')
    }
    for (let index = 1; index <= 4; index++) {
      if (editor.isActive('heading', { level: index })) {
        selectedStyle.value = styleOptions.value.find(o => o.key == `h${index}`)
      }
    }
    // updates selectedInfoBox value
    if (!editor.isActive('infoBox')) {
      selectedInfoBox.value = null
    } else {
      infoBoxOptions.value.forEach(option => {
        if (editor.isActive('infoBox', { 'data-type': option.key })) {
          selectedInfoBox.value = option
        }
      })
    }
  })
})
</script>

<template>
  <div
    class="relative sticky z-20 inline-flex text-sm bg-white border divide-x rounded-md w-fit top-header"
    v-if="editor"
  >
    <div class="relative">
      <Listbox v-model="selectedStyle" @update:model-value="onStyleInput">
        <ListboxButton class="listbox-button" v-slot="{ open, value }"
          >{{ value.label }}
          <icon v-if="open" name="arrow-up"></icon>
          <icon v-else name="arrow-down"></icon>
        </ListboxButton>
        <ListboxOptions class="listbox-options">
          <ListboxOption
            v-for="option in styleOptions"
            :key="option.key"
            :value="option"
            class="listbox-option"
          >
            {{ option.label }}
          </ListboxOption>
        </ListboxOptions>
      </Listbox>
    </div>
    <button
      @click="editor.chain().focus().toggleBold().run()"
      :class="[
        ,
        [editor.isActive('bold') ? 'text-black' : ' text-gray-400'],
        'toolbarButton',
      ]"
    >
      <icon name="bold" />
    </button>
    <button
      @click="editor.chain().focus().toggleItalic().run()"
      :class="[
        [editor.isActive('italic') ? 'text-black' : ' text-gray-400'],
        'toolbarButton',
      ]"
    >
      <icon name="italic" />
    </button>
    <button
      @click="editor.chain().focus().toggleUnderline().run()"
      :class="[
        [editor.isActive('underline') ? 'text-black' : ' text-gray-400'],
        'toolbarButton',
      ]"
    >
      <icon name="underline" />
    </button>
    <button
      @click="editor.chain().focus().toggleStrike().run()"
      :class="[
        [editor.isActive('strike') ? 'text-black' : ' text-gray-400'],
        'toolbarButton',
      ]"
    >
      <icon name="strikethrough" />
    </button>

    <button
      @click="editor.chain().focus().toggleBulletList().run()"
      :class="[
        [editor.isActive('bulletList') ? 'text-black' : ' text-gray-400'],
        'toolbarButton',
      ]"
    >
      <icon name="unorderedlist"></icon>
    </button>
    <button
      @click="editor.chain().focus().toggleOrderedList().run()"
      :class="[
        [editor.isActive('orderedList') ? 'text-black' : ' text-gray-400'],
        'toolbarButton',
      ]"
    >
      <icon name="orderedlist"></icon>
    </button>

    <div class="relative">
      <Listbox v-model="selectedLink" @update:model-value="onLinkInput">
        <ListboxButton class="listbox-button" v-slot="{ open }"
          ><icon class="text-gray-400 size-4" name="attachment"></icon>
          <icon v-if="open" name="arrow-up"></icon>
          <icon v-else name="arrow-down"></icon>
        </ListboxButton>
        <ListboxOptions class="listbox-options">
          <ListboxOption
            v-for="option in linkOptions"
            :key="option.key"
            :value="option"
            class="listbox-option"
          >
            {{ option.label }}
          </ListboxOption>
        </ListboxOptions>
      </Listbox>
    </div>
    <button
      @click="insertAttachmentAsImage()"
      :class="[
        [editor.isActive('image') ? 'text-black' : ' text-gray-400'],
        'toolbarButton',
      ]"
    >
      <icon name="image"></icon>
    </button>

    <div class="relative">
      <Listbox v-model="selectedInfoBox" @update:model-value="onInfoBoxInput">
        <ListboxButton class="listbox-button" v-slot="{ open }"
          ><icon
            :class="[
              [selectedInfoBox ? 'text-black' : ' text-gray-400'],
              'size-4',
            ]"
            :name="selectedInfoBox ? selectedInfoBox.key : 'warning'"
          ></icon>
          <icon v-if="open" name="arrow-up"></icon>
          <icon v-else name="arrow-down"></icon>
        </ListboxButton>
        <ListboxOptions class="listbox-options">
          <ListboxOption
            v-for="option in infoBoxOptions"
            :key="option.key"
            :value="option"
            class="listbox-option"
          >
            <icon :name="option.key"></icon> {{ option.label }}
          </ListboxOption>
        </ListboxOptions>
      </Listbox>
    </div>
  </div>
</template>

<style scoped>
.toolbarButton {
  @apply px-3 py-2;
}
.listbox-button {
  @apply relative inline-flex items-center h-full gap-2 text-left cursor-default toolbarButton;
}
.listbox-options {
  @apply absolute overflow-auto text-gray-300 bg-white divide-y shadow-lg rounded-b-md;
}
.listbox-option {
  @apply list-none cursor-pointer whitespace-nowrap toolbarButton hover:text-black;
}
</style>
