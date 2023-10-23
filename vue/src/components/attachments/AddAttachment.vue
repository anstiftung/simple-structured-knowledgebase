<script setup>
import { ref, defineProps } from 'vue'

import AttachmentTypeSelector from './AttachmentTypeSelector.vue'
import AttachFiles from './AttachFiles.vue'
import AttachUrls from './AttachUrls.vue'
import EditAttachments from './EditAttachments.vue'
import AttachmentService from '@/services/AttachmentService'

const props = defineProps({
  recipe: Object,
})

const editConfigUrls = {
  titleAttribute: 'url',
  inputs: [
    {
      attribute: 'title',
      type: 'text',
      label: 'Titel',
    },
    {
      attribute: 'description',
      type: 'text',
      label: 'Beschreibung',
    },
  ],
  labels: [
    {
      attribute: 'created_at',
      type: 'date',
      label: 'Erstellt',
    },
  ],
}

const editConfigFiles = {
  titleAttribute: 'filename',
  inputs: [
    {
      attribute: 'title',
      type: 'text',
      label: 'Titel',
    },
    {
      attribute: 'description',
      type: 'text',
      label: 'Beschreibung',
    },
    {
      attribute: 'source',
      type: 'text',
      label: 'Quelle',
    },
  ],
  labels: [
    {
      attribute: 'created_at',
      type: 'date',
      label: 'Erstellt',
    },
  ],
}

const savedFileList = ref([])
const savedUrlList = ref([])
const attachmentMode = ref('file')

const setMode = mode => {
  attachmentMode.value = mode
}

const persistFiles = fileList => {
  AttachmentService.createAttachmentFiles(fileList, props.recipe).then(data => {
    savedFileList.value = data
  })
}

const persistUrls = urlList => {
  // persist data with the AttachmentService; removes empty url objects
  AttachmentService.createAttachmentUrls(
    urlList.filter(i => i.url != ''),
    props.recipe,
  ).then(data => {
    savedUrlList.value = data
  })
}

const edited = () => {
  savedUrlList.value = []
  savedFileList.value = []
}
</script>

<template>
  <div class="my-8">
    <edit-attachments
      v-if="savedUrlList.length"
      type="url"
      :data="savedUrlList"
      :editConfig="editConfigUrls"
      @edited="edited"
    />
    <edit-attachments
      v-else-if="savedFileList.length"
      :data="savedFileList"
      type="file"
      :editConfig="editConfigFiles"
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
        @persist="persistFiles"
      ></attach-files>
      <attach-urls
        v-show="attachmentMode == 'url'"
        @persist="persistUrls"
      ></attach-urls>
    </div>
  </div>
</template>

<style scoped></style>
