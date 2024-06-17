<script setup>
const props = defineProps({
  attachments: Object,
})

const previewableMimes = ['image/png', 'image/jpg', 'image/jpeg']

const has_preview = mime => {
  return previewableMimes.includes(mime)
}
</script>
<template>
  <table class="w-full mb-4 table-fixed" v-if="attachments.length > 0">
    <thead class="sticky w-full text-gray-400 bg-white border-y top-0">
      <tr>
        <td class="px-2 py-3 w-5/12">Titel</td>
        <td class="px-2 py-3 w-3/12">Ersteller:in</td>
        <td class="px-2 py-3">Datum</td>
        <td class="px-2 py-3">Typ</td>
        <td class="px-2 py-3">Größe</td>
      </tr>
    </thead>
    <tbody>
      <tr
        v-for="attachment in attachments"
        :class="attachment.deleted_at ? 'opacity-50' : ''"
      >
        <td>
          <div
            class="cursor-pointer flex items-center"
            @click.prevent="$emit('resultSelected', attachment)"
          >
            <div
              class="flex items-center gap-2 px-2 py-3 font-semibold text-green w-full"
            >
              <!-- Preview Image or Placeholder-->
              <div>
                <img
                  :src="attachment.serve_url"
                  class="rounded-full h-6 w-6 inline-block mr-2"
                  v-if="has_preview(attachment.mime_type)"
                />
                <div class="w-6 h-6 mr-2" v-else />
              </div>
              <div class="flex w-full">
                <!-- Titel -->
                <div class="whitespace-nowrap">{{ attachment.title }}</div>
                <!-- If URL: add shortened url -->
                <div
                  v-if="attachment.type == 'AttachedUrl'"
                  class="pl-2 pr-8 font-normal text-gray-400 text-ellipsis overflow-hidden whitespace-nowrap"
                >
                  {{ attachment.serve_url }}
                </div>
              </div>
            </div>
          </div>

          <icon
            name="trash"
            class="text-gray-400"
            v-if="attachment.deleted_at"
          ></icon>
        </td>
        <td class="px-2 py-3">
          {{ attachment.created_by.name }}
        </td>
        <td class="px-2 py-3">
          {{ $filters.formatedDate(attachment.created_at) }}
        </td>
        <td class="px-2 py-3">
          {{ attachment.mime_type ? attachment.mime_type : 'url' }}
        </td>
        <td class="px-2 py-3">
          {{
            attachment.filesize
              ? $filters.bytesToHumandReadableSize(attachment.filesize)
              : ''
          }}
        </td>
      </tr>
    </tbody>
  </table>
</template>
