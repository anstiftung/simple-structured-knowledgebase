<script setup>
const props = defineProps({
  article: Object,
})
</script>

<template>
  <router-link
    :to="{
      name: 'article',
      params: { slug: article.slug },
    }"
    tag="div"
    class="p-5 text-white bg-orange rounded-b-md rounded-tl-md rounded-tr-[3rem] min-h-[280px] drop-shadow-md flex flex-col gap-2"
  >
    <h4 class="text-xl font-medium">{{ article.title }}</h4>
    <div class="grow">
      <p class="text-sm text-white/70 line-clamp-5">
        {{ article.description }}
      </p>
    </div>
    <p class="flex justify-between text-sm text-white/70">
      <span>{{ $filters.formatedDate(article.created_at) }}</span>
      <span
        >{{ article.num_attachments }}
        {{ article.num_attachments == 1 ? 'Anhang' : 'Anhänge' }}</span
      >
    </p>
  </router-link>
</template>

<style scoped></style>
