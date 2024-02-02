<script setup>
import { ref } from 'vue'
import AttachmentService from '@/services/AttachmentService'
const props = defineProps({
  dataType: String,
  dataId: String,
  href: String,
  target: String,
})
const attachedFile = ref(null)

const loadFromServer = () => {
  if (props.dataType == 'AttachedFile') {
    AttachmentService.getAttachedFile(props.dataId).then(data => {
      attachedFile.value = data
    })
  }
}
loadFromServer()
</script>

<template>
  <a :data-type="props['dataType']" :href="props.href">
    <slot></slot>
    <span
      class="inline-block ml-2 text-sm"
      v-if="attachedFile && attachedFile.filesize"
      >{{ $filters.bytesToHumandReadableSize(attachedFile.filesize) }}</span
    >
  </a>
</template>

<style scoped></style>
