<script setup>
import { ref, defineProps, computed, defineEmits } from 'vue'

import AttachmentTypeSelector from './AttachmentTypeSelector.vue'
import AttachFiles from './AttachFiles.vue'
import AttachUrls from './AttachUrls.vue'
import EditAttachments from './EditAttachments.vue'

const props = defineProps({
  recipe: Object,
})

const emit = defineEmits(['changed'])

const savedFileList = ref([])
const savedUrlList = ref([])
const attachmentMode = ref('file')

const setMode = mode => {
  attachmentMode.value = mode
}

const persistedFiles = fileList => {
  savedFileList.value = fileList
}

const persistedUrls = urlList => {
  savedUrlList.value = urlList
}

const editState = computed(() => {
  if (savedUrlList.value.length) {
    return 'url'
  } else if (savedFileList.value.length) {
    return 'file'
  } else {
    return false
  }
})
const editData = computed(() => {
  if (editState.value == 'url') {
    return savedUrlList.value
  } else if (editState.value == 'file') {
    return savedFileList.value
  }
})

const edited = () => {
  savedUrlList.value = []
  savedFileList.value = []
  emit('changed')
}
</script>

<template>
  <div class="my-8">
    <edit-attachments
      v-if="editState"
      :type="editState"
      :data="editData"
      @edited="edited"
    />
    <div class="relative flex flex-col gap-6 px-8 py-12 bg-gray-100" v-else>
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
        <attachment-type-selector @mode="setMode" :mode="attachmentMode" />
      </div>
      <attach-files
        v-show="attachmentMode == 'file'"
        @persisted="persistedFiles"
        :recipe="recipe"
      ></attach-files>
      <attach-urls
        v-show="attachmentMode == 'url'"
        @persisted="persistedUrls"
        :recipe="recipe"
      ></attach-urls>
    </div>
  </div>
</template>

<style scoped></style>
