<script setup>
import { ref, defineProps } from 'vue'

import AttachmentService from '@/services/AttachmentService'
import AttachmentTypeSelector from './AttachmentTypeSelector.vue'
import AttachFiles from './AttachFiles.vue'
import AttachUrls from './AttachUrls.vue'

const props = defineProps({
  recipe: Object,
})

const fileList = ref([])
const attachmentMode = ref('file')

const setMode = mode => {
  attachmentMode.value = mode
}

const persistFiles = list => {
  console.log('Persist files: ', list, ' for recipe: ', props.recipe.id)
}
const persistUrls = list => {
  //persist data with the AttachmentService
  AttachmentService.createAttachmentUrls(list, props.recipe).then(
    ({ data, meta }) => {
      console.log(data, meta)
    },
  )
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
    <attach-files
      v-show="attachmentMode == 'file'"
      @persist="persistFiles"
    ></attach-files>
    <attach-urls
      v-show="attachmentMode == 'url'"
      @persist="persistUrls"
    ></attach-urls>
  </div>
</template>

<style scoped></style>
