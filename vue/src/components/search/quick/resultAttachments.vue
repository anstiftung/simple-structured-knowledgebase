<script setup>
const props = defineProps({
  attachments: Object,
})
</script>
<template>
  <table class="w-full mb-4" v-if="attachments.length > 0">
    <thead class="sticky w-full text-gray-400 bg-white border-y top-0">
      <tr>
        <td class="px-2 py-3">Titel</td>
        <td class="px-2 py-3">Ersteller:in</td>
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
        <td class="flex items-center gap-2 px-2 py-3 font-semibold text-green">
          <router-link :to="attachment.url" class="cursor-pointer">
            {{ attachment.title }}
            <span
              v-if="attachment.type == 'AttachedUrl'"
              class="font-normal text-black"
            >
              {{ attachment.serve_url }}
            </span>
          </router-link>
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
