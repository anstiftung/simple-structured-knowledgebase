<template>
  <component
    :is="iconComponent"
    role="img"
    class="inline-block fill-current"
    style="height: 1em; width: 1em"
  />
</template>

<script setup>
import { defineProps, computed, markRaw, defineAsyncComponent } from 'vue'

const icons = markRaw({
  image: defineAsyncComponent(() =>
    import('@/components/icons/icons/ImageIcon.vue'),
  ),
  attachment: defineAsyncComponent(() =>
    import('@/components/icons/icons/AttachmentIcon.vue'),
  ),
  bold: defineAsyncComponent(() =>
    import('@/components/icons/icons/BoldIcon.vue'),
  ),
  italic: defineAsyncComponent(() =>
    import('@/components/icons/icons/ItalicIcon.vue'),
  ),
  'ordered-list': defineAsyncComponent(() =>
    import('@/components/icons/icons/OrderedListIcon.vue'),
  ),
  'unordered-list': defineAsyncComponent(() =>
    import('@/components/icons/icons/UnorderedListIcon.vue'),
  ),
  underline: defineAsyncComponent(() =>
    import('@/components/icons/icons/UnderlineIcon.vue'),
  ),
  strikethrough: defineAsyncComponent(() =>
    import('@/components/icons/icons/StrikethroughIcon.vue'),
  ),
})

const props = defineProps({
  name: {
    type: String,
    required: true,
  },
})

const iconComponent = computed(() => {
  return icons[props.name]
})
</script>
