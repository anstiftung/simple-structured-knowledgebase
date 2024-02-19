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
    // extract slug from href prop because the getArticle route uses slugs instead of id's
    let slug = null
    const urlParts = props.href.split('/')
    if (urlParts && urlParts.length == 3) {
      slug = urlParts[urlParts.length - 1]
    } else {
      console.error('Unable to parse slug from article url: ', props.href)
      return
    }
    ArticleService.getArticle(slug).then(data => {
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
        <icon name="comment" class="text-gray-400 size-3"></icon>
      </template>
    </span>
  </a>
</template>

<style scoped></style>
