<template>
  <th
    @click="$emit('sortChanged', name)"
    class="cursor-pointer underline decoration-dotted"
  >
    <slot></slot>
    <span v-if="sortBy === name && sortOrder == 'asc'"
      ><icon name="arrow-up" class="ml-1" />
    </span>
    <span v-if="sortBy === name && sortOrder == 'desc'">
      <icon name="arrow-down" class="ml-1" />
    </span>
  </th>
</template>
<script setup>
import { computed, defineProps, defineEmits } from 'vue'

const props = defineProps({
  name: {
    type: String,
    required: true,
  },
  sortBy: {
    type: String,
    required: false,
  },
  sortOrder: {
    type: String,
    required: false,
  },
})

const emit = defineEmits(['sortChanged'])
</script>
