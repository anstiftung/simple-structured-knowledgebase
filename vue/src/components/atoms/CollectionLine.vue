<script setup>
const props = defineProps({
  collection: Object,
  dragable: {
    type: Boolean,
    default: false,
  },
})
</script>

<template>
  <p>
    <span v-if="!dragable">
      <router-link
        class="font-semibold text-blue-400"
        :to="{
          name: 'collection',
          params: { slug: collection.slug },
        }"
      >
        {{ collection.title }}
      </router-link>
    </span>
    <span v-else class="font-semibold text-blue-400">
      <svg
        xmlns="http://www.w3.org/2000/svg"
        width="1rem"
        height="1rem"
        fill="#000000"
        viewBox="0 0 256 256"
        class="inline-block cursor-grab mr-1"
      >
        <path
          d="M104,60A12,12,0,1,1,92,48,12,12,0,0,1,104,60Zm60,12a12,12,0,1,0-12-12A12,12,0,0,0,164,72ZM92,116a12,12,0,1,0,12,12A12,12,0,0,0,92,116Zm72,0a12,12,0,1,0,12,12A12,12,0,0,0,164,116ZM92,184a12,12,0,1,0,12,12A12,12,0,0,0,92,184Zm72,0a12,12,0,1,0,12,12A12,12,0,0,0,164,184Z"
        ></path>
      </svg>
      <router-link
        class="font-semibold text-blue-400"
        :to="{
          name: 'collection',
          params: { slug: collection.slug },
        }"
      >
        {{ collection.title }}
      </router-link>
    </span>
    <span class="inline-block ml-2 text-gray-200">{{
      $filters.formatedDate(collection.created_at)
    }}</span>
  </p>
</template>

<style scoped></style>
