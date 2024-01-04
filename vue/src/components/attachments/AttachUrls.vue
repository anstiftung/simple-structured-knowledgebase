<script setup>
import { ref, watch } from 'vue'
import AttachmentListItem from './AttachmentListItem.vue'
import AttachmentService from '@/services/AttachmentService'

const emit = defineEmits(['persisted', 'update:dirty'])
const props = defineProps({
  article: Object,
})

const urlList = ref([{ url: '' }])

// automaticly adds an empty url object to the list; triggers dirty event for unsaved urls
watch(
  urlList,
  () => {
    let numEmptyItems = 0
    let numFilledItems = 0
    urlList.value.forEach(i => {
      if (i.url.length == 0) {
        numEmptyItems++
      } else {
        numFilledItems++
      }
    })
    if (numEmptyItems == 0) {
      addURL()
    }

    if (numFilledItems > 0) {
      emit('update:dirty', true)
    } else {
      emit('update:dirty', false)
    }
  },
  { deep: true },
)

const addURL = () => {
  urlList.value.push({ url: '' })
}

const removeUrlFromList = url => {
  urlList.value = urlList.value.filter(e => e.url != url.url)
}

const persist = () => {
  // persist data with the AttachmentService; removes empty url objects
  AttachmentService.createAttachmentUrls(
    urlList.value.filter(i => i.url != ''),
    props.article,
  ).then(data => {
    urlList.value = []
    emit('persisted', data)
  })
}
</script>

<template>
  <div class="relative flex flex-col gap-4">
    <attachment-list-item
      v-for="url in urlList"
      :url="url"
      @remove="removeUrlFromList"
    ></attachment-list-item>
    <div
      :class="[
        'w-full px-4 py-4 text-center text-white rounded-md',
        [urlList.length ? 'bg-blue' : 'bg-gray-200'],
      ]"
      role="button"
      @click="persist"
    >
      {{ urlList.length > 1 ? 'URLs' : 'URL' }} speichern
    </div>
  </div>
</template>

<style scoped></style>
