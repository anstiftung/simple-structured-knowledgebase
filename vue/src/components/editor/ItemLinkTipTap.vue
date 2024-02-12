<script setup>
import { NodeViewContent, nodeViewProps, NodeViewWrapper } from '@tiptap/vue-3'
const props = defineProps({
  node: {
    type: Object,
    required: true,
  },
})
</script>

<template>
  <node-view-wrapper class="inline-block">
    <a :data-type="props.node.attrs['data-type']">
      <node-view-content class="content" />
    </a>
  </node-view-wrapper>
</template>

<style scoped></style>
