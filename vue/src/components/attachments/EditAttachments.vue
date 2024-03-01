<script setup>
import { computed, ref, reactive, inject } from 'vue'
import { useVuelidate } from '@vuelidate/core'
import { helpers } from '@vuelidate/validators'
import { required$, maxLength$ } from '@/plugins/validators.js'
import { storeToRefs } from 'pinia'

import { useUserStore } from '@/stores/user'

import LicenseSelect from '@/components/atoms/LicenseSelect.vue'
import AttachmentService from '@/services/AttachmentService'
import InputWithCounter from '@/components/atoms/InputWithCounter.vue'

const props = defineProps({
  attachments: Array,
})

const userStore = useUserStore()

const { hasPermission } = storeToRefs(userStore)

const emit = defineEmits(['done'])
const $toast = inject('$toast')

// save to local reactive that allows modifying the list
const formData = reactive({
  attachmentList: [...props.attachments],
})
const rules = {
  attachmentList: {
    $each: helpers.forEach({
      title: { required$, maxLength: maxLength$(100) },
      description: { required$, maxLength: maxLength$(250) },
      source: { required$, maxLength: maxLength$(400) },
      license: { required$ },
    }),
  },
}

const v$ = useVuelidate(rules, formData)

const editConfigUrls = {
  titleAttribute: 'url',
  inputs: [
    {
      attribute: 'title',
      type: 'text',
      label: 'Titel',
      maxlength: 100,
    },
    {
      attribute: 'description',
      type: 'text',
      label: 'Beschreibung',
      maxlength: 250,
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
      maxlength: 100,
    },
    {
      attribute: 'description',
      type: 'text',
      label: 'Beschreibung',
      maxlength: 250,
    },
    {
      attribute: 'source',
      type: 'text',
      label: 'Quelle',
      maxlength: 400,
    },
    {
      attribute: 'license',
      type: 'license',
      label: 'Lizenz',
    },
  ],
  labels: [
    {
      attribute: 'filesize',
      type: 'filesize',
      label: 'Dateigröße',
    },
    {
      attribute: 'mime_type',
      type: 'mime_type',
      label: 'Typ',
    },
    {
      attribute: 'created_at',
      type: 'date',
      label: 'Erstellt',
    },
  ],
}

const getEditConfig = (attachment, attribute) => {
  if (attachment.type === 'AttachedFile') {
    return editConfigFiles[attribute]
  } else if (attachment.type === 'AttachedUrl') {
    return editConfigUrls[attribute]
  }
}

const visibleIndex = ref(0)

const currentAttachment = computed(() => {
  return formData.attachmentList[visibleIndex.value]
})

const nextHint = computed(() => {
  const attachmentsLeft =
    formData.attachmentList.length - 1 - visibleIndex.value
  if (attachmentsLeft > 1) {
    return `Benenne noch weitere ${attachmentsLeft} Anhänge`
  } else if (attachmentsLeft == 1) {
    return `Benenne noch einen weiteren Anhang`
  } else {
    return false
  }
})

const prevValid = computed(() => {
  return visibleIndex.value > 0
})
const nextValid = computed(() => {
  return visibleIndex.value < formData.attachmentList.length - 1
})
const next = () => {
  if (nextValid.value) {
    visibleIndex.value++
  }
}
const prev = () => {
  if (prevValid.value) {
    visibleIndex.value--
  }
}

const save = async () => {
  const formIsCorret = await v$.value.$validate()
  if (!formIsCorret) {
    $toast.error('Formular ungültig')
    return
  }

  let filesToSave = []
  let urlsToSave = []
  formData.attachmentList.forEach(attachment => {
    if (attachment.type === 'AttachedFile') {
      filesToSave.push(attachment)
    } else if (attachment.type === 'AttachedUrl') {
      urlsToSave.push(attachment)
    }
  })

  let promises = []
  if (filesToSave.length > 0) {
    promises.push(AttachmentService.updateAttachmentFiles(filesToSave))
  }
  if (urlsToSave.length > 0) {
    promises.push(AttachmentService.updateAttachmentUrls(urlsToSave))
  }
  Promise.all(promises).then(values => {
    $toast.success(
      `Erfolgreich ${formData.attachmentList.length} ${
        formData.attachmentList.length == 1 ? 'Anhang' : 'Anhänge'
      } gespeichert`,
    )
    emit('done')
  })
}
</script>

<template>
  <div class="w-full h-full p-4 text-white bg-green">
    <h4 class="font-semibold">
      {{
        currentAttachment[getEditConfig(currentAttachment, 'titleAttribute')]
      }}
    </h4>
    <div class="flex flex-col items-start gap-4 my-6 md:flex-row">
      <div class="flex flex-col w-full gap-4 grow">
        <template v-for="field in getEditConfig(currentAttachment, 'inputs')">
          <div
            class="text-sm text-red"
            v-for="error of v$.attachmentList.$each.$response.$errors[
              visibleIndex
            ][field.attribute]"
            :key="error.$uid"
          >
            <div>! {{ error.$message }}</div>
          </div>
          <template v-if="field.type == 'license'">
            <license-select v-model="currentAttachment[field.attribute]" />
          </template>
          <template v-else>
            <input-with-counter
              v-if="field.maxlength"
              class="w-full px-4 py-3 text-gray-800 rounded-md"
              :placeholder="field.label"
              :type="field.type"
              :maxlength="field.maxlength"
              v-model="currentAttachment[field.attribute]"
              position="right"
            />
            <input
              v-else
              class="w-full px-4 py-3 text-gray-800 rounded-md"
              :placeholder="field.label"
              :type="field.type"
              v-model="currentAttachment[field.attribute]"
            />
          </template>
        </template>
        <div
          v-if="hasPermission('approve content')"
          class="flex items-center gap-2 my-4"
        >
          <input
            type="checkbox"
            class="size-5"
            v-model="currentAttachment.approved"
            id="approved"
          />
          <label for="approved">Vom Legal Team geprüfter Artikel</label>
        </div>
      </div>
      <div class="grid w-full grid-cols-2 gap-2 text-sm text-white md:w-auto">
        <template v-for="field in getEditConfig(currentAttachment, 'labels')">
          <span>{{ field.label }}:</span>
          <span v-if="field.type == 'date'">
            {{ $filters.formatedDateTime(currentAttachment[field.attribute]) }}
          </span>
          <span v-else-if="field.type == 'mime_type'"
            >{{ $filters.fileNameToFileType(currentAttachment.filename) }}
          </span>
          <span v-else-if="field.type == 'filesize'">
            {{
              $filters.bytesToHumandReadableSize(
                currentAttachment[field.attribute],
              )
            }}
          </span>
          <span v-else>
            {{ currentAttachment[field.attribute] }}
          </span>
        </template>
      </div>
    </div>
    <div class="flex items-center justify-between my-6">
      <a role="button" @click="prev" v-show="prevValid">
        <svg
          xmlns="http://www.w3.org/2000/svg"
          width="40"
          height="40"
          viewBox="0 0 66 66"
          fill="none"
        >
          <circle cx="33" cy="33" r="33" fill="white" />
          <path
            d="M38.8392 50.4734L42.4526 46.9997C42.8056 46.6604 43 46.2085 43 45.7272C43 45.2457 42.8055 44.7935 42.4522 44.4541L30.5269 32.999L42.4522 21.5427C42.8055 21.2034 43 20.7513 43 20.2699C43 19.7884 42.8055 19.3364 42.4522 18.997L38.8393 15.5262C38.4861 15.1869 38.0155 15 37.5144 15C37.0132 15 36.5426 15.1869 36.1894 15.5262L18 33L36.1893 50.4738C36.5426 50.8132 37.0132 51 37.5144 51C38.0156 50.9999 38.4861 50.8129 38.8392 50.4734Z"
            fill="#0F3F8F"
          />
        </svg>
      </a>
      <a class="grow"></a>
      <a
        role="button"
        class="flex items-center gap-2"
        @click="next"
        v-show="nextValid"
      >
        <span v-if="nextHint" class="text-sm">{{ nextHint }}</span>
        <svg
          xmlns="http://www.w3.org/2000/svg"
          width="40"
          height="40"
          viewBox="0 0 66 66"
          fill="none"
        >
          <circle
            cx="33"
            cy="33"
            r="33"
            transform="rotate(-180 33 33)"
            fill="white"
          />
          <path
            d="M27.1608 15.5266L23.5474 19.0003C23.1944 19.3396 23 19.7915 23 20.2728C23 20.7543 23.1946 21.2065 23.5478 21.5459L35.4731 33.001L23.5478 44.4573C23.1945 44.7966 23 45.2487 23 45.7301C23 46.2116 23.1945 46.6636 23.5478 47.003L27.1607 50.4738C27.5139 50.8131 27.9845 51 28.4856 51C28.9868 51 29.4574 50.8131 29.8106 50.4738L48 33L29.8107 15.5262C29.4574 15.1868 28.9868 15 28.4857 15C27.9844 15.0001 27.5139 15.1871 27.1608 15.5266Z"
            fill="#0F3F8F"
          /></svg
      ></a>
    </div>
    <div
      class="w-full px-4 py-4 text-center text-white rounded-md bg-blue"
      role="button"
      @click="save"
    >
      {{ formData.attachmentList.length > 1 ? 'Alle Anhänge' : 'Anhang' }}
      speichern
    </div>
  </div>
</template>

<style scoped></style>
