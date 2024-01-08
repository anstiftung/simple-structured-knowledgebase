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
    class="p-4 text-white bg-orange"
  >
    <h4 class="font-bold">{{ article.title }}</h4>
    <p class="my-4">{{ article.description }}</p>
    <p class="text-sm">
      Aktualisiert am: {{ $filters.formatedDateTime(article.updated_at) }} von
      {{ article.updated_by.name }}
    </p>
    <p class="text-sm">
      Erstellt am: {{ $filters.formatedDateTime(article.created_at) }} von
      {{ article.created_by.name }}
    </p>
  </router-link>
</template>

<style scoped></style>
