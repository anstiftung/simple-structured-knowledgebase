<script setup>
const props = defineProps({
  colorClass: String,
  secondaryColorClass: String,
})
</script>

<template>
  <section :class="secondaryColorClass">
    <div :class="['header-clip', colorClass]">
      <div class="py-12 text-center text-white width-wrapper">
        <h3 class="mb-2 font-normal text-center opacity-70">
          <slot name="description"></slot>
        </h3>
        <slot name="content"></slot>
      </div>
    </div>
  </section>
</template>

<style scoped>
.header-clip {
  clip-path: polygon(0px 0px, 0 80%, 100% 100%, 100% 0px);
}
</style>
