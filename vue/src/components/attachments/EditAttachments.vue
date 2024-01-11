<script setup>
import { computed, ref } from 'vue'
import { useToast } from 'vue-toastification'
import LicenseSelect from '@/components/fields/LicenseSelect.vue'
import AttachmentService from '@/services/AttachmentService'

const props = defineProps({
  data: Array,
  type: String,
})

const emit = defineEmits(['edited'])
const toast = useToast()

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
    {
      attribute: 'license',
      type: 'license',
      label: 'Lizens',
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

const editConfig = computed(() => {
  if (props.type == 'url') {
    return editConfigUrls
  } else if (props.type == 'file') {
    return editConfigFiles
  }
})

const visibleIndex = ref(0)
// save to local ref that allows modifying the list
const attachmentList = ref([...props.data])

const currentAttachment = computed(() => {
  return attachmentList.value[visibleIndex.value]
})

const nextHint = computed(() => {
  const attachmentsLeft = attachmentList.value.length - 1 - visibleIndex.value
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
  return visibleIndex.value < attachmentList.value.length - 1
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

const save = () => {
  let request = null
  if (props.type == 'file') {
    request = AttachmentService.updateAttachmentFiles(attachmentList.value)
  }
  if (props.type == 'url') {
    request = AttachmentService.updateAttachmentUrls(attachmentList.value)
  }
  request.then(data => {
    toast.success(
      `Erfolgreich ${data.length} ${
        data.length == 1 ? 'Anhang' : 'Anhänge'
      } gespeichert`,
    )
    emit('edited')
  })
}
</script>

<template>
  <div class="absolute top-0 left-0 z-20 w-full h-full p-4 text-white bg-green">
    <h4 class="font-semibold">
      {{ currentAttachment[editConfig.titleAttribute] }}
    </h4>
    <div class="flex flex-col items-start gap-4 my-6 md:flex-row">
      <div class="flex flex-col w-full gap-4 grow">
        <template v-for="field in editConfig.inputs">
          <template v-if="field.type == 'license'">
            <license-select v-model="currentAttachment[field.attribute]" />
          </template>
          <input
            v-else
            class="w-full max-w-xl px-4 py-3 text-gray-800 rounded-md"
            :placeholder="field.label"
            :type="field.type"
            v-model="currentAttachment[field.attribute]"
          />
        </template>
      </div>
      <div
        class="grid w-full grid-cols-2 gap-2 text-sm text-gray-400 md:w-auto"
      >
        <template v-for="field in editConfig.labels" class="">
          <span>{{ field.label }}:</span>
          <span v-if="field.type == 'date'">
            {{ $filters.formatedDateTime(currentAttachment[field.attribute]) }}
          </span>
          <span v-else-if="field.type == 'mime_type'"
            >{{
              $filters.mimeTypeToFileType(currentAttachment[field.attribute])
            }}
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
      <a role="button" @click="prev">
        <svg
          xmlns="http://www.w3.org/2000/svg"
          width="40"
          height="40"
          viewBox="0 0 66 66"
          fill="none"
          :class="[prevValid ? 'opacity-100' : 'opacity-50']"
        >
          <circle cx="33" cy="33" r="33" fill="white" />
          <path
            d="M38.8392 50.4734L42.4526 46.9997C42.8056 46.6604 43 46.2085 43 45.7272C43 45.2457 42.8055 44.7935 42.4522 44.4541L30.5269 32.999L42.4522 21.5427C42.8055 21.2034 43 20.7513 43 20.2699C43 19.7884 42.8055 19.3364 42.4522 18.997L38.8393 15.5262C38.4861 15.1869 38.0155 15 37.5144 15C37.0132 15 36.5426 15.1869 36.1894 15.5262L18 33L36.1893 50.4738C36.5426 50.8132 37.0132 51 37.5144 51C38.0156 50.9999 38.4861 50.8129 38.8392 50.4734Z"
            fill="#0F3F8F"
          />
        </svg>
      </a>
      <a role="button" class="flex items-center gap-2" @click="next">
        <span v-if="nextHint" class="text-sm">{{ nextHint }}</span>
        <svg
          xmlns="http://www.w3.org/2000/svg"
          width="40"
          height="40"
          viewBox="0 0 66 66"
          fill="none"
          :class="[nextValid ? 'opacity-100' : 'opacity-50']"
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
      {{ data.length > 1 ? 'Alle Anhänge' : 'Anhang' }} speichern
    </div>
  </div>
</template>

<style scoped></style>
