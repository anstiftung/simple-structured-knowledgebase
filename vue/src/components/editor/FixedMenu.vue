<script setup>
import { ref, onMounted } from 'vue'
import { useModalStore } from '@/stores/modal'

import AddAttachments from '@/components/attachments/AddAttachments.vue'
import EditAttachments from '@/components/attachments/EditAttachments.vue'

import ModelSelector from '@/components/search/ModelSelector.vue'
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

/* allows selecting an item for inserting it as a link */
const selectItemForInsert = type => {
  modal.open(ModelSelector, { modelType: type }, item => {
    if (item) {
      insertItemAsLink(item)
    }
  })
}

/* allows selecting an attachments of type image for inserting it as an image */
const selectImageForInsert = () => {
  modal.open(ModelSelector, { modelType: 'images' }, item => {
    if (item) {
      insertAttachmentsAsImages([item])
    }
  })
}

/* creates an attachment an inserts is a link */
const createAttachmentForInsert = () => {
  modal.open(AddAttachments, {}, savedAttachments => {
    if (savedAttachments && savedAttachments.length) {
      const modalProps = { attachments: savedAttachments }
      modal.open(EditAttachments, modalProps, savedAttachments => {
        savedAttachments.forEach(item => {
          insertItemAsLink(item)
        })
      })
    }
  })
}

/* creates an attachment of type image and inserts it as an image */
const createImageForInsert = () => {
  modal.open(AddAttachments, { imageMode: true }, savedAttachments => {
    if (savedAttachments && savedAttachments.length) {
      const modalProps = { attachments: savedAttachments }
      modal.open(EditAttachments, modalProps, savedAttachments => {
        insertAttachmentsAsImages(savedAttachments)
      })
    }
  })
}

const insertAttachmentsAsImages = items => {
  if (!items) {
    return
  }
  let contentToInsert = []
  items.forEach(item => {
    // only insert files of type image as <img tag – otherwise fall back to link
    if (item.is_image) {
      contentToInsert.push({
        type: 'image',
        attrs: {
          src: item.serve_url,
          alt: item.title,
          title: '(c) ' + item.source,
        },
      })
    } else {
      insertItemAsLink(item)
    }
  })
  // workaround: add empty paragraph after image
  contentToInsert.push({ type: 'text', text: ' ' })
  props.editor.commands.insertContent(contentToInsert)
}

/* inserts an item (article|collection|attachment) as link */
const insertItemAsLink = item => {
  if (!item) {
    return
  }
  // get current text selection
  const { view, state } = props.editor
  const { from, to } = view.state.selection
  let text = state.doc.textBetween(from, to, '')

  let attributes = {
    href: item.url,
    'data-type': item.type,
    'data-id': item.id,
  }
  // lets open links to attachments in a new tab
  if (item.type == 'AttachedUrl' || item.type == 'AttachedFile') {
    attributes['target'] = '_blank'
  }

  // fallback for empty text selection: title of the model
  if (!text) {
    text = item.title
  }

  // insert itemLink with attributes and content, add space after itemLink.
  props.editor.commands.insertContent([
    {
      type: 'itemLink',
      attrs: attributes,
      content: [
        {
          type: 'text',
          text: text,
        },
      ],
    },
    { type: 'text', text: ' ' },
  ])
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
    class="relative sticky z-20 text-sm text-center bg-white top-header"
    v-if="editor"
  >
    <div class="inline-flex border-b border-l border-r divide-x rounded-b-md">
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
        <Listbox>
          <ListboxButton class="listbox-button" v-slot="{ open }"
            ><icon class="text-gray-400 size-4" name="attachment"></icon>
            <icon v-if="open" name="arrow-up"></icon>
            <icon v-else name="arrow-down"></icon>
          </ListboxButton>
          <ListboxOptions class="listbox-options">
            <ListboxOption
              class="listbox-option"
              @click="selectItemForInsert('collections')"
              >Sammlung verlinken</ListboxOption
            >
            <ListboxOption
              class="listbox-option"
              @click="selectItemForInsert('articles')"
              >Beitrag verlinken</ListboxOption
            >
            <ListboxOption
              class="listbox-option"
              @click="selectItemForInsert('attachments')"
              >Anhang verlinken</ListboxOption
            >
            <ListboxOption
              class="listbox-option"
              @click="createAttachmentForInsert()"
              >Anhang erstellen</ListboxOption
            >
          </ListboxOptions>
        </Listbox>
      </div>

      <div class="relative">
        <Listbox>
          <ListboxButton class="listbox-button" v-slot="{ open }"
            ><icon class="text-gray-400 size-4" name="image"></icon>
            <icon v-if="open" name="arrow-up"></icon>
            <icon v-else name="arrow-down"></icon>
          </ListboxButton>
          <ListboxOptions class="listbox-options">
            <ListboxOption
              class="listbox-option"
              @click="selectImageForInsert()"
              >Bild einfügen</ListboxOption
            >
            <ListboxOption
              class="listbox-option"
              @click="createImageForInsert()"
              >Bild hochladen</ListboxOption
            >
          </ListboxOptions>
        </Listbox>
      </div>

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
  </div>
</template>

<style scoped>
.toolbarButton {
  @apply px-3 py-2;
}
.listbox-button {
  @apply relative inline-flex items-center h-full gap-2 text-left cursor-default whitespace-nowrap toolbarButton;
}
.listbox-options {
  @apply absolute overflow-auto text-gray-300 bg-white divide-y shadow-lg rounded-b-md;
}
.listbox-option {
  @apply list-none cursor-pointer whitespace-nowrap toolbarButton hover:text-black text-left;
}
</style>
