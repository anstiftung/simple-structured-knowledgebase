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

const model = ref(null)

const loadFromServer = () => {
  let getPromise
  if (props.dataType == 'AttachedFile') {
    getPromise = AttachmentService.getAttachedFile(
      props.dataId,
      false,
      () => {}, // silent errors
    )
  }

  if (props.dataType == 'AttachedUrl') {
    getPromise = AttachmentService.getAttachedUrl(props.dataId, false, () => {}) // silent errors
  }

  // currently we don not load collections in item link but we want to show them as a tag anyway
  if (props.dataType == 'Collection') {
    model.value = true
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

    getPromise = ArticleService.getArticle(slug, () => {})
  }
  if (getPromise) {
    getPromise.then(data => {
      model.value = data
    })
  }
}
loadFromServer()
</script>

<template>
  <component
    :is="model ? 'a' : 'span'"
    :data-type="props['dataType']"
    :href="props.href"
    :target="props.target"
  >
    <slot></slot>
    <span
      class="inline-block ml-2 text-xs font-semibold text-gray-400"
      v-if="model"
    >
      <template v-if="model.type == 'AttachedFile'"
        >{{ $filters.fileNameToFileType(model.filename) }},
        {{ $filters.bytesToHumandReadableSize(model.filesize) }}
        <icon
          v-if="model.approved"
          name="approved"
          class="mb-1 text-green size-3"
        ></icon>
      </template>
      <template v-if="model.type == 'Article'">
        {{ model.comments.length }}
        <icon name="comment" class="text-gray-400 size-3"></icon>
        <icon
          v-if="model.approved"
          name="approved"
          class="mb-1 ml-1 text-green size-3"
        ></icon>
      </template>
    </span>
  </component>
</template>

<style scoped></style>
