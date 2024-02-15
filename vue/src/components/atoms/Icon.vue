<script setup>
/*
 * Simple and Easy external SVG icon component
 * just place the svg you wish to use in /public/icons
 * match the filename and the id of the svg-element inside
 * load the file via <icon name="filename" />
 * Make Sure to replace all fill colors inside the svg if you want to make use of currentColor-CSS-Property
 * The icon's default size is 15x15px; the provided svg file should have a viewBox of 15x15 as well
 * the icon is scalable by the tailwind `size-x` class
 */

import { computed } from 'vue'

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

<template>
  <svg
    fill="currentColor"
    class="inline-block"
    viewBox="0 0 15 15"
    width="15px"
    height="15px"
  >
    <use :href="fileLink" />
  </svg>
</template>
