<script setup>
import { computed } from 'vue'

const props = defineProps({
  model: Object,
  showType: {
    type: Boolean,
    default: true,
  },
  navigate: {
    type: Boolean,
    default: true,
  },
})
</script>

<template>
  <p>
    <span class="mr-1" v-if="showType">
      <template v-if="model.type == 'Article'">Beitrag</template>
      <template v-else-if="model.type == 'Collection'">Sammlung</template>
      <template v-else>Anhang</template>
    </span>

    <span
      v-if="model.type == 'Article'"
      class="font-semibold cursor-pointer text-orange"
    >
      <router-link v-if="navigate" :to="model.url">
        {{ model.title }}
      </router-link>
      <template v-else>{{ model.title }}</template>
    </span>

    <span
      class="font-semibold text-blue-400 cursor-pointer"
      v-else-if="model.type == 'Collection'"
    >
      <router-link v-if="navigate" :to="model.url">
        {{ model.title }}
      </router-link>
      <template v-else>{{ model.title }}</template>
    </span>

    <!-- attachments -->
    <span v-else class="font-semibold text-green">
      {{ model.title ?? '[Ohne Titel]' }}
    </span>

    <span class="inline-block ml-2 text-gray-200"
      >erstellt {{ $filters.formatedDate(model.created_at) }}</span
    >
  </p>
</template>

<style scoped></style>
