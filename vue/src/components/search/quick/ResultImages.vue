<script setup>
const props = defineProps({
  images: Object,
})
</script>
<template>
  <table class="w-full mb-4" v-if="images.length > 0">
    <thead class="sticky w-full text-gray-400 bg-white border-y top-0">
      <tr>
        <td class="px-2 py-3">Titel</td>
        <td class="px-2 py-3">Datum</td>
        <td class="px-2 py-3">geändert</td>
        <td class="px-2 py-3">Typ</td>
        <td class="px-2 py-3">Größe</td>
      </tr>
    </thead>
    <tbody>
      <tr
        v-for="attachment in images"
        :class="attachment.deleted_at ? 'opacity-50' : ''"
      >
        <td class="flex items-center gap-2 px-2 py-3 font-semibold text-green">
          <div
            class="cursor-pointer flex items-center"
            @click.prevent="$emit('resultSelected', attachment)"
          >
            <img
              :src="attachment.serve_url"
              class="rounded-full h-8 w-8 inline mr-2"
              v-if="attachment.type == 'Image'"
            />

            {{ attachment.title }}
          </div>
          <icon
            name="trash"
            class="text-gray-400"
            v-if="attachment.deleted_at"
          ></icon>
        </td>
        <td class="px-2 py-3">
          {{ $filters.formatedDate(attachment.created_at) }}
        </td>
        <td class="px-2 py-3">
          {{ $filters.formatedDate(attachment.updated_at) }}
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
