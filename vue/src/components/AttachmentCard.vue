<script setup>
const props = defineProps({
  attachment: Object,
})
</script>

<template>
  <div class="p-4 text-white bg-green">
    <h4 class="font-bold">{{ attachment.title }}</h4>
    <p class="my-4">{{ attachment.description }}</p>
    <template v-if="attachment.url">
      <p class="my-4">URL: {{ attachment.url }}</p>
    </template>
    <template v-if="attachment.filename">
      <p class="my-4">filename: {{ attachment.filename }}</p>
      <img :src="'/api/attachedFile/' + attachment.id" />
    </template>
    <p class="text-sm">
      Aktualisiert am:
      {{ $filters.formatedDateTime(attachment.updated_at) }} von
      {{ attachment.updated_by.name }}
    </p>
    <p class="text-sm">
      Erstellt am: {{ $filters.formatedDateTime(attachment.created_at) }} von
      {{ attachment.created_by.name }}
    </p>
  </div>
</template>

<style scoped></style>
