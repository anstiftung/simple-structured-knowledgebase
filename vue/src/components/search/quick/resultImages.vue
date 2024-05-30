<script setup>
const props = defineProps({
  images: Object,
})
</script>
<template>
  <tbody>
    <tr
      v-for="attachment in images"
      :class="attachment.deleted_at ? 'opacity-50' : ''"
    >
      <td class="flex items-center gap-2 px-2 py-3 font-semibold text-green">
        <router-link :to="attachment.url" class="cursor-pointer">
          {{ attachment.title }}
        </router-link>
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
</template>
