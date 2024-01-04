<script setup>
import { ref, defineEmits, defineProps, watch } from 'vue'
import AttachmentListItem from './AttachmentListItem.vue'
import AttachmentService from '@/services/AttachmentService'

const emit = defineEmits(['persisted', 'update:dirty'])
const props = defineProps({
  article: Object,
})
const isDragging = ref(false)
const uploadProgress = ref(0)
const fileList = ref([])

var dragCounter = 0
const dragEnter = () => {
  dragCounter++
  isDragging.value = true
}
const dragLeave = () => {
  dragCounter--
  if (dragCounter === 0) {
    isDragging.value = false
  }
}

const dropHandler = event => {
  isDragging.value = false
  addFilesToList(event.dataTransfer.files)
  event.preventDefault()
}

const chooseFile = event => {
  addFilesToList(event.target.files)
}

const dragOverHandler = event => {
  event.preventDefault()
}

const addFilesToList = filesToAdd => {
  ;[...filesToAdd].forEach((file, i) => {
    fileList.value.push(file)
  })
  uploadProgress.value = 0
}

const removeFileFromList = file => {
  fileList.value = fileList.value.filter(
    e => e.name != file.name || e.size != file.size,
  )
  uploadProgress.value = 0
}

watch(
  fileList,
  () => {
    if (fileList.value.length > 0) {
      emit('update:dirty', true)
    } else if (fileList.value.length == 0) {
      emit('update:dirty', false)
    }
  },
  { deep: true },
)

const progressCallback = percent => {
  uploadProgress.value = percent
}

const persist = () => {
  AttachmentService.createAttachmentFiles(
    fileList.value,
    props.article,
    progressCallback,
  )
    .then(data => {
      emit('persisted', data)
      fileList.value = []
      uploadProgress.value = 0
    })
    .catch(() => {
      uploadProgress.value = 0
    })
}
</script>

<template>
  <div class="flex flex-col gap-6">
    <div
      class="border border-gray-400 border-solid"
      :ondrop="dropHandler"
      :ondragover="dragOverHandler"
      v-on:dragenter="dragEnter"
      v-on:dragleave="dragLeave"
    >
      <div class="flex flex-col items-center justify-center gap-4 p-16">
        <svg
          xmlns="http://www.w3.org/2000/svg"
          width="88"
          height="112"
          viewBox="0 0 88 112"
          fill="none"
        >
          <path
            d="M77 82.32H11C9.79005 82.32 8.80005 83.328 8.80005 84.5601V106.96C8.80005 108.192 9.79005 109.2 11 109.2H77C78.21 109.2 79.2 108.192 79.2 106.96V84.5601C79.2 83.328 78.21 82.32 77 82.32ZM68.64 98.4481H19.36V93.0721H68.64V98.4481ZM18.6247 32.1651C18.8823 32.1651 19.1739 32.076 19.4823 31.8876L38.7115 20.1372V68.8801H49.2715V20.1319L68.5093 31.8876C68.8176 32.076 69.1092 32.1651 69.3668 32.1651C69.9763 32.1651 70.3958 31.666 70.3958 30.8001V20.4971L43.9958 4.37085L17.5958 20.4971V30.8001C17.5958 31.6659 18.0152 32.1651 18.6247 32.1651Z"
            :fill="isDragging ? 'black' : '#CBCBCB'"
          />
        </svg>
        <p class="text-gray-400">
          <template v-if="isDragging">Elemente zum Hochladen ablegen</template>
          <template v-else>
            Ein kleiner Hilfetext, dass hier Dateien (in welchem Format)
            reingedroppt werden können.
          </template>
        </p>
        <label
          for="upload-file"
          class="p-2 text-sm text-white rounded-md cursor-pointer bg-blue"
        >
          Dateien auswählen
          <input
            type="file"
            id="upload-file"
            multiple
            hidden
            @change="chooseFile"
          />
        </label>
      </div>
    </div>
    <div v-if="fileList.length" class="flex flex-col gap-4">
      <attachment-list-item
        v-for="file in fileList"
        :file="file"
        @remove="removeFileFromList"
      ></attachment-list-item>
    </div>
    <div
      :class="[
        'w-full px-4 py-4 text-center text-white rounded-md relative overflow-hidden',
        [fileList.length ? 'bg-blue' : 'bg-gray-200'],
      ]"
      role="button"
      @click="persist"
    >
      <div
        class="absolute top-0 bottom-0 left-0 z-0 transition-all bg-green"
        :style="{ width: uploadProgress + '%' }"
      ></div>
      <span class="relative z-10"
        >{{ fileList.length > 1 ? 'Dateien' : 'Datei' }} hochladen</span
      >
    </div>
  </div>
</template>

<style scoped></style>
