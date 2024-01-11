<script setup>
import { ref, computed } from 'vue'
import { useToast } from 'vue-toastification'

import AttachmentTypeSelector from './AttachmentTypeSelector.vue'
import AttachFiles from './AttachFiles.vue'
import AttachUrls from './AttachUrls.vue'
import EditAttachments from './EditAttachments.vue'

const props = defineProps({
  article: Object,
})
const emit = defineEmits(['changed'])

const toast = useToast()

const filesDirty = ref(false)
const savedFileList = ref([])
const urlsDirty = ref(false)
const savedUrlList = ref([])

const attachmentMode = ref('file')

const setMode = newMode => {
  attachmentMode.value = newMode
  if (newMode == 'file' && urlsDirty.value == true) {
    toast.warning(`Achtung! Ungespeicherte URL's`)
  }
  if (newMode == 'url' && filesDirty.value == true) {
    toast.warning(`Achtung! Ungespeicherte Dateien`)
  }
}

const persistedFiles = fileList => {
  savedFileList.value = fileList
  filesDirty.value = false
}

const persistedUrls = urlList => {
  savedUrlList.value = urlList
  urlsDirty.value = false
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
  <div class="relative my-8 min-h-[400px]">
    <edit-attachments
      v-if="editState"
      :type="editState"
      :data="editData"
      @edited="edited"
    />
    <div class="relative flex flex-col gap-6 px-8 py-12 bg-gray-100">
      <div class="flex justify-between">
        <div>
          <h3 class="text-xl">
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
        :article="article"
        v-model:dirty="filesDirty"
      ></attach-files>
      <attach-urls
        v-show="attachmentMode == 'url'"
        @persisted="persistedUrls"
        :article="article"
        v-model:dirty="urlsDirty"
      ></attach-urls>
    </div>
  </div>
</template>

<style scoped></style>
