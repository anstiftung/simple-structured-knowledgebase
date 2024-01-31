<template>
  <svg fill="currentColor" height="1em" width="1em">
    <use :href="fileLink" />
  </svg>
</template>

<script setup>
import { defineProps, computed } from 'vue'

import attachmentIcon from '@/public/icons/attachment.svg'

const props = defineProps({
  name: {
    type: String,
    required: true,
  },
})

const fileLink = computed(() => {
  return '/icons/' + props.name + '.svg#' + props.name
})
</script>
