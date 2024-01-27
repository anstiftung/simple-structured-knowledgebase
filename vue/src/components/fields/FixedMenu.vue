<script setup>
import { ref, computed, onMounted } from 'vue'
import { useModalStore } from '@/stores/modal'
import BaseIcon from '../icons/BaseIcon.vue'

import ModelSelector from '@/components/atoms/ModelSelector.vue'

const modal = useModalStore()

const props = defineProps({
  editor: Object,
})

const selectedStyle = ref('p')

const options = ref({
  p: { label: 'Absatz' },
  h2: { label: 'Ü2', level: 2 },
  h3: { label: 'Ü3', level: 3 },
  h4: { label: 'Ü4', level: 4 },
})

const onStyleInput = () => {
  if (selectedStyle.value === 'p') {
    props.editor.chain().focus().clearNodes().run()
  } else {
    const level = options.value[selectedStyle.value].level
    props.editor.chain().focus().setHeading({ level: level }).run()
  }
}

const mode = computed(() => {
  return props.editor.isActive('heading', { level: 1 })
})

const toggleLinkSelection = type => {
  if (props.editor.isActive('link')) {
    props.editor.chain().focus().extendMarkRange('link').unsetLink().run()
    return
  }
  const componentProps = {
    modelType: type,
  }
  modal.open(ModelSelector, componentProps, selection => {
    if (selection) {
      let attributes = {
        href: selection.url,
        'data-type': selection.type,
      }
      // lets open links to attachments in a new tab
      if (selection.type == 'AttachedUrl' || selection.type == 'AttachedFile') {
        attributes['target'] = '_blank'
      }
      props.editor
        .chain()
        .focus()
        .extendMarkRange('link')
        .setLink(attributes)
        .run()
    }
  })
}

const editorLinkActive = linkType => {
  if (props.editor.isActive('link') && props.editor.isFocused) {
    let type = props.editor.getAttributes('link')['data-type']
    if (type == 'AttachedFile' || type == 'AttachedUrl') {
      type = 'Attachment'
    }
    return type == linkType
  }
  return false
}

onMounted(() => {
  props.editor.on('selectionUpdate', ({ editor }) => {
    if (editor.isActive('paragraph')) {
      selectedStyle.value = 'p'
    }
    for (let index = 1; index <= 4; index++) {
      if (editor.isActive('heading', { level: index })) {
        selectedStyle.value = `h${index}`
      }
    }
  })
})
</script>

<template>
  <div class="sticky z-20 flex gap-2 bg-white top-header" v-if="editor">
    <select
      class="secondary-button"
      @change="onStyleInput"
      v-model="selectedStyle"
    >
      <option v-for="(value, key) in options" :value="key">
        {{ value.label }}
      </option>
    </select>

    <button
      @click="editor.chain().focus().toggleBulletList().run()"
      :class="[
        [editor.isActive('bulletList') ? 'bg-gray-200' : ''],
        'secondary-button',
      ]"
    >
      <base-icon name="unordered-list"></base-icon>
    </button>
    <button
      @click="editor.chain().focus().toggleOrderedList().run()"
      :class="[
        [editor.isActive('orderedList') ? 'bg-gray-200' : ''],
        'secondary-button',
      ]"
    >
      <base-icon name="ordered-list"></base-icon>
    </button>

    <button
      :class="[
        [editorLinkActive('Article') ? 'bg-gray-200' : ''],
        'secondary-button',
      ]"
      @click="toggleLinkSelection('articles')"
    >
      Beitrag
    </button>
    <button
      :class="[
        [editorLinkActive('Collection') ? 'bg-gray-200' : ''],
        'secondary-button',
      ]"
      @click="toggleLinkSelection('collections')"
    >
      Sammlung
    </button>
    <button
      @click="toggleLinkSelection('attachments')"
      :class="[
        [editorLinkActive('Attachment') ? 'bg-gray-200' : ''],
        'secondary-button',
      ]"
    >
      <base-icon name="attachment"></base-icon>
    </button>
    <button
      @click="toggleLinkSelection('attachments')"
      :class="[
        [editorLinkActive('Attachment') ? 'bg-gray-200' : ''],
        'secondary-button',
      ]"
    >
      <base-icon name="image"></base-icon>
    </button>
  </div>
</template>

<style scoped></style>
