<script setup>
const props = defineProps({
  attachment: Object,
})
</script>

<template>
  <a :href="attachment.url" target="_blank">
    <div
      class="flex flex-col justify-between h-full px-4 py-4 text-white rounded-lg cursor-pointer bg-green"
    >
      <h4 class="font-medium break-words line-clamp-2">
        <template v-if="attachment.title">{{ attachment.title }}</template>
        <template v-else-if="attachment.type == 'AttachedFile'">
          {{ attachment.filename }}
        </template>
      </h4>
      <p class="flex justify-between text-sm text-gray-100">
        <span v-if="attachment.type == 'AttachedFile'">
          {{ $filters.fileNameToFileType(attachment.filename) }} |
          {{ $filters.bytesToHumandReadableSize(attachment.filesize) }}
        </span>
        <span v-else class="line-clamp-1" :title="attachment.title">
          {{ attachment.serve_url }}
        </span>
        <icon v-if="attachment.approved" name="approved"></icon>
      </p>
    </div>
  </a>
</template>

<style scoped></style>
