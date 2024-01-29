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
  dragable: {
    type: Boolean,
    default: false,
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

    <!-- articles -->
    <span
      v-if="model.type == 'Article'"
      class="font-semibold cursor-pointer text-orange"
    >
      <svg
        xmlns="http://www.w3.org/2000/svg"
        width="1rem"
        height="1rem"
        fill="#000000"
        viewBox="0 0 256 256"
        class="inline-block mr-1 cursor-grab"
        v-if="dragable"
      >
        <path
          d="M104,60A12,12,0,1,1,92,48,12,12,0,0,1,104,60Zm60,12a12,12,0,1,0-12-12A12,12,0,0,0,164,72ZM92,116a12,12,0,1,0,12,12A12,12,0,0,0,92,116Zm72,0a12,12,0,1,0,12,12A12,12,0,0,0,164,116ZM92,184a12,12,0,1,0,12,12A12,12,0,0,0,92,184Zm72,0a12,12,0,1,0,12,12A12,12,0,0,0,164,184Z"
        ></path>
      </svg>
      <router-link v-if="navigate" :to="model.url">
        {{ model.title }}
      </router-link>
      <template v-else>{{ model.title }}</template>
    </span>

    <!-- collections -->
    <span
      class="font-semibold text-blue-400 cursor-pointer"
      v-else-if="model.type == 'Collection'"
    >
      <svg
        xmlns="http://www.w3.org/2000/svg"
        width="1rem"
        height="1rem"
        fill="#000000"
        viewBox="0 0 256 256"
        class="inline-block mr-1 cursor-grab"
        v-if="dragable"
      >
        <path
          d="M104,60A12,12,0,1,1,92,48,12,12,0,0,1,104,60Zm60,12a12,12,0,1,0-12-12A12,12,0,0,0,164,72ZM92,116a12,12,0,1,0,12,12A12,12,0,0,0,92,116Zm72,0a12,12,0,1,0,12,12A12,12,0,0,0,164,116ZM92,184a12,12,0,1,0,12,12A12,12,0,0,0,92,184Zm72,0a12,12,0,1,0,12,12A12,12,0,0,0,164,184Z"
        ></path>
      </svg>
      <router-link v-if="navigate" :to="model.url">
        {{ model.title }}
      </router-link>
      <template v-else>{{ model.title }}</template>
    </span>

    <!-- attachments -->
    <a
      v-else
      :href="model.url"
      target="_blank"
      class="font-semibold cursor-pointer text-green"
    >
      {{ model.title ?? '[Ohne Titel]' }}
    </a>

    <span class="inline-block ml-2 text-gray-200"
      >erstellt {{ $filters.formatedDate(model.created_at) }}</span
    >
  </p>
</template>

<style scoped></style>
