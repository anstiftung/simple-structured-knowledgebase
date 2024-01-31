<template>
  <svg fill="currentColor" height="1em" width="1em">
    <use :href="fileLink" />
  </svg>
</template>

<script setup>
/*
 * Simple and Easy external SVG icon component
 * just place the svg you wish to use in /public/icons
 * match the filename and the id of the svg-element inside
 * load the file via <icon name="filename" />
 * Make Sure to replace all fill colors inside the svg if you want to make use of currentColor-CSS-Property
 */

import { defineProps, computed } from 'vue'

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
