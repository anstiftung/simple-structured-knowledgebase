<script setup>
import { computed } from 'vue'

const emit = defineEmits(['remove'])
const props = defineProps({
  file: Object,
  url: Object,
  errors: Object,
})

const removeAttachment = () => {
  if (props.file) {
    emit('remove', props.file)
  } else {
    emit('remove', props.url)
  }
}

const fileURL = computed(() => {
  if (props.file.type == 'image/jpeg' || props.file.type == 'image/png') {
    return URL.createObjectURL(props.file)
  } else {
    return false
  }
})

const fileType = computed(() => {
  switch (props.file.type) {
    case 'text/rtf':
      return 'RTF'
    case 'application/pdf':
      return 'PDF'
    default:
      return ''
      break
  }
})
</script>

<template>
  <div class="flex items-center gap-4 p-2 bg-white rounded-md">
    <div
      v-if="file"
      class="flex items-center justify-center w-8 h-8 overflow-hidden text-xs font-bold bg-gray-200 rounded-md"
    >
      <img class="object-cover w-full h-full" v-if="fileURL" :src="fileURL" />
      <template v-else>{{ fileType }}</template>
    </div>
    <p v-if="file" class="text-blue grow">{{ file.name }}</p>
    <input
      v-if="url"
      type="text"
      v-model="url.url"
      placeholder="URL einfügen"
      class="px-4 py-2 outline-none grow text-blue"
    />
    <div
      class="text-sm text-red"
      v-if="errors"
      v-for="error of errors"
      :key="error.$uid"
    >
      <div>! {{ error.$message }}</div>
    </div>
    <a class="cursor-pointer" @click="removeAttachment">
      <icon name="trash" />
    </a>
  </div>
</template>

<style scoped></style>
