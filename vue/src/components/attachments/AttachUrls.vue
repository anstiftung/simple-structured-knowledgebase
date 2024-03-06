<script setup>
import { reactive, watch, computed, inject } from 'vue'
import { useVuelidate } from '@vuelidate/core'
import { helpers } from '@vuelidate/validators'
import { url$ } from '@/plugins/validators.js'

import AttachmentListItem from '@/components/attachments/AttachmentListItem.vue'
import AttachmentService from '@/services/AttachmentService'

const emit = defineEmits(['persisted', 'update:dirty'])
const props = defineProps({
  article: Object,
})
const $toast = inject('$toast')

const formData = reactive({
  urlList: [{ url: '' }],
})

const rules = {
  urlList: {
    $each: helpers.forEach({
      url: { url$ },
    }),
  },
}

const v$ = useVuelidate(rules, formData)

// automaticly adds an empty url object to the list; triggers dirty event for unsaved urls
watch(
  () => formData,
  formData => {
    let numEmptyItems = 0
    let numFilledItems = 0
    formData.urlList.forEach(i => {
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
  formData.urlList.push({ url: '' })
}

const removeUrlFromList = url => {
  formData.urlList = formData.urlList.filter(e => e.url != url.url)
}

const persist = async () => {
  const formIsCorret = await v$.value.$validate()
  if (!formIsCorret) {
    $toast.error('Formular ungültig')
    return
  }
  // persist data with the AttachmentService; removes empty url objects
  AttachmentService.createAttachmentUrls(
    formData.urlList.filter(i => i.url != ''),
    props.article,
  ).then(data => {
    formData.urlList = []
    emit('persisted', data)
  })
}

const urlListValid = computed(async () => {
  return (
    formData.urlList.filter(i => i.url != '').length &&
    (await v$.value.$validate())
  )
})
</script>

<template>
  <div class="relative flex flex-col gap-4">
    <attachment-list-item
      v-for="(url, index) in formData.urlList"
      :url="url"
      @remove="removeUrlFromList"
      :errors="v$.urlList.$each.$response.$errors[index].url"
    ></attachment-list-item>
    <button
      :class="[
        'w-full px-4 py-4 text-center text-white rounded-md',
        [urlListValid ? 'bg-blue' : 'bg-gray-200'],
      ]"
      :disabled="!urlListValid"
      @click="persist"
    >
      {{ urlListValid > 1 ? 'URLs' : 'URL' }} speichern
    </button>
  </div>
</template>

<style scoped></style>
