<script setup>
const props = defineProps({
  model: Object,
})
</script>

<template>
  <p>
    <span>{{ model.type == 'Article' ? 'Beitrag ' : 'Anhang ' }}</span>
    <router-link
      class="font-semibold text-orange"
      v-if="model.type == 'Article'"
      :to="{
        name: 'article',
        params: { slug: model.slug },
      }"
    >
      {{ model.title }}
    </router-link>
    <span v-else class="font-semibold text-green">
      {{ model.title ?? '[Ohne Titel]' }}
    </span>
    <span> erstellt</span>
    <span class="inline-block ml-2 text-gray-200">{{
      $filters.formatedDate(model.created_at)
    }}</span>
  </p>
</template>

<style scoped></style>
