<script setup>
import { ref } from 'vue'
import AttachmentTypeSelector from './AttachmentTypeSelector.vue'
import AttachFiles from './AttachFiles.vue'
import AttachUrls from './AttachUrls.vue'

const fileList = ref([])
const attachmentMode = ref('file')

const setMode = mode => {
  attachmentMode.value = mode
}
</script>

<template>
  <div class="flex flex-col gap-6 px-8 py-12 my-8 bg-gray-100">
    <div class="flex justify-between">
      <div>
        <h3 class="text-xl font-bold">
          <template v-if="attachmentMode == 'file'">Datenupload</template>
          <template v-else>URL hinzufügen</template>
        </h3>
        <p class="text-gray-400">
          Lorem ipsum dolor sit amet, consetetur sadipscing elitr
        </p>
      </div>
      <attachment-type-selector @mode="setMode" />
    </div>
    <attach-files v-show="attachmentMode == 'file'"></attach-files>
    <attach-urls v-show="attachmentMode == 'url'"></attach-urls>
    <div
      :class="[
        'w-full px-4 py-4 text-center text-white rounded-md',
        [fileList.length ? 'bg-blue' : 'bg-gray-200'],
      ]"
      role="button"
      @click="startUpload"
    >
      Alles hinzufügen
    </div>
  </div>
</template>

<style scoped></style>
