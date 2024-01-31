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
  <node-view-wrapper class="vue-component">
    <span class="label" contenteditable="false">Vue Component</span>
    <node-view-content class="content" />
  </node-view-wrapper>
</template>

<style scoped>
.vue-component {
  background: #faf594;
  border: 3px solid #0d0d0d;
  border-radius: 0.5rem;
  margin: 1rem 0;
  position: relative;
}

.label {
  margin-left: 1rem;
  background-color: #0d0d0d;
  font-size: 0.6rem;
  letter-spacing: 1px;
  font-weight: bold;
  text-transform: uppercase;
  color: #fff;
  position: absolute;
  top: 0;
  padding: 0.25rem 0.75rem;
  border-radius: 0 0 0.5rem 0.5rem;
}

.content {
  margin: 2.5rem 1rem 1rem;
  padding: 0.5rem;
  border: 2px dashed #0d0d0d20;
  border-radius: 0.5rem;
}
</style>
