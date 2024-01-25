<script setup>
import { computed } from 'vue'

const props = defineProps({
  model: Object,
})
</script>

<template>
  <p>
    <span class="mr-1">
      <template v-if="model.type == 'Article'">Beitrag</template>
      <template v-else-if="model.type == 'Collection'">Sammlung</template>
      <template v-else>Anhang</template>
    </span>

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

    <router-link
      class="font-semibold text-blue-400"
      v-else-if="model.type == 'Collection'"
      :to="{
        name: 'collection',
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
