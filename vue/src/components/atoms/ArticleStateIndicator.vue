<script setup>
defineOptions({
  inheritAttrs: false,
})

const props = defineProps({
  article: Object,
})
</script>
<template>
  <span class="ml-1">
    <span
      v-if="article.state.key == 'review'"
      title="Zum Review eingereicht"
      class="w-full h-full"
    >
      <icon name="book-open-user" v-bind="$attrs" />
    </span>
    <span v-if="article.state.key == 'draft'" title="nicht Veröffentlicht">
      <icon name="book" v-bind="$attrs" />
    </span>
  </span>
</template>
