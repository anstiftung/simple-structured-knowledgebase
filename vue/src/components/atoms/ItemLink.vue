<script setup>
import { ref } from 'vue'
import AttachmentService from '@/services/AttachmentService'
import ArticleService from '@/services/ArticleService'

const props = defineProps({
  dataType: String,
  dataId: String,
  href: String,
  target: String,
})

const attachedFile = ref(null)
const article = ref(null)

const loadFromServer = () => {
  if (props.dataType == 'AttachedFile') {
    AttachmentService.getAttachedFile(props.dataId).then(data => {
      attachedFile.value = data
    })
  }

  if (props.dataType == 'Article') {
    ArticleService.getArticle(props.dataId).then(data => {
      article.value = data
    })
  }
}
loadFromServer()
</script>

<template>
  <a :data-type="props['dataType']" :href="props.href">
    <slot></slot>
    <span class="inline-block ml-2 text-xs font-semibold text-gray-400">
      <template v-if="attachedFile"
        >{{ $filters.fileNameToFileType(attachedFile.filename) }},
        {{ $filters.bytesToHumandReadableSize(attachedFile.filesize) }}
      </template>
      <template v-if="article">
        {{ article.comments.length }}
        <img class="inline-block m-0 size-6/12" src="/icons/comment.svg" />
      </template>
    </span>
  </a>
</template>

<style scoped></style>
