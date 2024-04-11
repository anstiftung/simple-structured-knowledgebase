<script setup>
import { ref, inject } from 'vue'

import AttachmentTypeSelector from '@/components/attachments/AttachmentTypeSelector.vue'
import AttachFiles from '@/components/attachments/AttachFiles.vue'
import AttachUrls from '@/components/attachments/AttachUrls.vue'

const props = defineProps({
  article: Object,
  imageMode: {
    type: Boolean,
    default: false,
  },
})
const emit = defineEmits(['done'])

const $toast = inject('$toast')

const filesDirty = ref(false)
const urlsDirty = ref(false)

const attachmentMode = ref('file')

const setMode = newMode => {
  attachmentMode.value = newMode
  if (newMode == 'file' && urlsDirty.value == true) {
    $toast.warning(`Achtung! Ungespeicherte URL's`)
  }
  if (newMode == 'url' && filesDirty.value == true) {
    $toast.warning(`Achtung! Ungespeicherte Dateien`)
  }
}

const persistedFiles = fileList => {
  filesDirty.value = false
  emit('done', fileList)
}

const persistedUrls = urlList => {
  urlsDirty.value = false
  emit('done', urlList)
}

const shouldCloseModal = done => {
  if (urlsDirty.value || filesDirty.value) {
    $toast.confirm('Ungespeicherte Änderungen! Vorgang wirklich beenden?', done)
  } else {
    done()
  }
}

defineExpose({
  shouldCloseModal,
})
</script>

<template>
  <div class="relative my-8 min-h-[400px]">
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
        <attachment-type-selector
          v-if="!imageMode"
          @mode="setMode"
          :mode="attachmentMode"
        />
      </div>
      <attach-files
        v-show="attachmentMode == 'file'"
        @persisted="persistedFiles"
        :article="article"
        v-model:dirty="filesDirty"
        :onlyImages="imageMode"
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
