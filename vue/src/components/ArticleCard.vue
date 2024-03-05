<script setup>
const props = defineProps({
  article: Object,
})
</script>

<template>
  <router-link :to="article.url" custom v-slot="{ href, navigate }">
    <div
      @click="navigate"
      class="cursor-pointer p-5 text-white bg-orange rounded-b-md rounded-tl-md rounded-tr-[3rem] min-h-[280px] drop-shadow-md flex flex-col gap-2"
    >
      <h4 class="text-xl font-medium">
        {{ article.title }}
      </h4>
      <div class="grow">
        <p class="text-sm text-white/70 line-clamp-5">
          {{ article.description }}
        </p>
      </div>
      <p class="flex items-end justify-between text-sm text-white/70">
        <span>{{ $filters.formatedDate(article.created_at) }}</span>
        <span class="flex gap-4">
          <span
            class="flex items-end gap-2"
            v-if="article.claps && article.claps > 0"
          >
            <icon name="clap" class="size-5"></icon>
            <span>{{ article.claps }}</span>
          </span>
          <icon v-if="article.approved" name="approved" class="size-5"></icon>
        </span>
      </p>
    </div>
  </router-link>
</template>

<style scoped></style>
