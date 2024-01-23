<script setup>
import { ref, computed, onMounted } from 'vue'

const props = defineProps({
  editor: Object,
})

const selectedStyle = ref('p')

const options = ref({
  p: { label: 'Absatz' },
  h1: { label: 'Ü1', level: 1 },
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
  <div class="flex gap-2" v-if="editor">
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
      Liste
    </button>
    <button
      @click="editor.chain().focus().toggleOrderedList().run()"
      :class="[
        [editor.isActive('orderedList') ? 'bg-gray-200' : ''],
        'secondary-button',
      ]"
    >
      Liste numeriert
    </button>
  </div>
</template>

<style scoped></style>
