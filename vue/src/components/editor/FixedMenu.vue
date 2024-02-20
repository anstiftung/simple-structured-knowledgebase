<script setup>
import { ref, computed, onMounted } from 'vue'
import { useModalStore } from '@/stores/modal'

import ModelSelector from '@/components/atoms/ModelSelector.vue'

const modal = useModalStore()

const props = defineProps({
  editor: Object,
})

/* dropdown with differenct node Styles */
const selectedStyle = ref('p')
const styleOptions = ref({
  p: { label: 'Absatz' },
  h2: { label: 'Ü2', level: 2 },
  h3: { label: 'Ü3', level: 3 },
  h4: { label: 'Ü4', level: 4 },
})

const onStyleInput = () => {
  if (selectedStyle.value === 'p') {
    props.editor.chain().focus().clearNodes().run()
  } else {
    const level = styleOptions.value[selectedStyle.value].level
    props.editor.chain().focus().setHeading({ level: level }).run()
  }
}

/* dropdown with differenct Infoboxes */
const selectedInfoBox = ref(null)
const infoBoxOptions = ref({
  warning: { label: 'Hinweis' },
  danger: { label: 'Gefahrenhinweis' },
  question: { label: 'Fragestellung' },
})

const onInfoBoxInput = () => {
  props.editor.commands.setNode('infoBox', {
    'data-type': selectedInfoBox.value,
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
    let test = props.editor.getAttributes('itemLink')
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

/* checks which of the above inserted links is currently active */
const editorLinkActive = linkType => {
  if (props.editor.isActive('itemLink') && props.editor.isFocused) {
    let type = props.editor.getAttributes('itemLink')['data-type']
    if (type == 'AttachedFile' || type == 'AttachedUrl') {
      type = 'Attachment'
    }
    return type == linkType
  }
  return false
}

/* allows inserting attachments as inline images */
const insertAttachmentAsImage = () => {
  modal.open(ModelSelector, { modelType: 'images' }, selection => {
    if (selection) {
      let attributes = {
        src: selection.url,
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
      selectedStyle.value = 'p'
    }
    for (let index = 1; index <= 4; index++) {
      if (editor.isActive('heading', { level: index })) {
        selectedStyle.value = `h${index}`
      }
    }
    // updates selectedInfoBox value
    if (!editor.isActive('infoBox')) {
      selectedInfoBox.value = null
    } else {
      Object.keys(infoBoxOptions.value).forEach(key => {
        if (editor.isActive('infoBox', { 'data-type': key })) {
          selectedInfoBox.value = key
        }
      })
    }
  })
})
</script>

<template>
  <div
    class="sticky z-20 inline-flex overflow-hidden bg-white border divide-x rounded-md top-header"
    v-if="editor"
  >
    <select
      class="toolbarButton"
      @change="onStyleInput"
      v-model="selectedStyle"
    >
      <option v-for="(value, key) in styleOptions" :value="key">
        {{ value.label }}
      </option>
    </select>

    <button
      @click="editor.chain().focus().toggleBold().run()"
      :class="[
        ,
        [editor.isActive('bold') ? 'bg-gray-100' : ''],
        'toolbarButton',
      ]"
    >
      <icon name="bold" />
    </button>
    <button
      @click="editor.chain().focus().toggleItalic().run()"
      :class="[
        [editor.isActive('italic') ? 'bg-gray-100' : ''],
        'toolbarButton',
      ]"
    >
      <icon name="italic" />
    </button>
    <button
      @click="editor.chain().focus().toggleUnderline().run()"
      :class="[
        [editor.isActive('underline') ? 'bg-gray-100' : ''],
        'toolbarButton',
      ]"
    >
      <icon name="underline" />
    </button>
    <button
      @click="editor.chain().focus().toggleStrike().run()"
      :class="[
        [editor.isActive('strike') ? 'bg-gray-100' : ''],
        'toolbarButton',
      ]"
    >
      <icon name="strikethrough" />
    </button>

    <button
      @click="editor.chain().focus().toggleBulletList().run()"
      :class="[
        [editor.isActive('bulletList') ? 'bg-gray-100' : ''],
        'toolbarButton',
      ]"
    >
      <icon name="unorderedlist"></icon>
    </button>
    <button
      @click="editor.chain().focus().toggleOrderedList().run()"
      :class="[
        [editor.isActive('orderedList') ? 'bg-gray-100' : ''],
        'toolbarButton',
      ]"
    >
      <icon name="orderedlist"></icon>
    </button>

    <button
      :class="[
        [editorLinkActive('Article') ? 'bg-gray-100' : ''],
        'toolbarButton',
      ]"
      @click="toggleLinkSelection('articles')"
    >
      Beitrag
    </button>
    <button
      :class="[
        [editorLinkActive('Collection') ? 'bg-gray-100' : ''],
        'toolbarButton',
      ]"
      @click="toggleLinkSelection('collections')"
    >
      Sammlung
    </button>
    <button
      @click="toggleLinkSelection('attachments')"
      :class="[
        [editorLinkActive('Attachment') ? 'bg-gray-100' : ''],
        'toolbarButton',
      ]"
    >
      <icon name="attachment"></icon>
    </button>
    <button
      @click="insertAttachmentAsImage()"
      :class="[
        [editor.isActive('image') ? 'bg-gray-100' : ''],
        'toolbarButton',
      ]"
    >
      <icon name="image"></icon>
    </button>
    <select
      class="toolbarButton"
      @change="onInfoBoxInput"
      v-model="selectedInfoBox"
    >
      <option disabled selected :value="null">InfoBox</option>
      <option v-for="(value, key) in infoBoxOptions" :value="key">
        {{ value.label }}
      </option>
    </select>
  </div>
</template>

<style scoped></style>
