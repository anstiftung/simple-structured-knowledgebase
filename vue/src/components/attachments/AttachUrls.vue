<script setup>
import { ref, watch, computed, defineEmits } from 'vue'
import AttachmentListItem from './AttachmentListItem.vue'

const emit = defineEmits(['persist'])

const emptyUrlObject = { url: '' }
const urlList = ref([emptyUrlObject])

// automaticly adds an empty url object to the list
watch(
  urlList,
  () => {
    let numEmptyItems = 0
    urlList.value.forEach(i => {
      if (i.url.length == 0) {
        numEmptyItems++
      }
    })
    if (numEmptyItems == 0) {
      addURL()
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
      @click="emit('persist', urlList)"
    >
      {{ urlList.length > 1 ? 'URLs' : 'URL' }} speichern
    </div>
  </div>
</template>

<style scoped></style>
