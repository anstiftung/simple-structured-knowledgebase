<script setup>
defineEmits(['resultSelected'])

const props = defineProps({
  articles: Object,
})
</script>
<template>
  <table class="w-full mb-4" v-if="articles.length > 0">
    <thead class="sticky w-full text-gray-400 bg-white border-y top-0">
      <tr>
        <td class="px-2 py-3">Titel</td>
        <td class="px-2 py-3">Ersteller:in</td>
        <td class="px-2 py-3">erstellt</td>
        <td class="px-2 py-3">Claps</td>
        <td class="px-2 py-3">Kommentare</td>
      </tr>
    </thead>
    <tbody>
      <tr
        v-for="article in articles"
        :class="article.deleted_at ? 'opacity-50' : ''"
      >
        <td
          class="px-2 py-1 font-semibold text-orange cursor-pointer"
          @click.prevent="$emit('resultSelected', article)"
        >
          {{ article.title }}
          <icon
            name="trash"
            class="text-gray-400"
            v-if="article.deleted_at"
          ></icon>
        </td>
        <td class="px-2 py-3">{{ article.created_by.name }}</td>
        <td class="px-2 py-3">
          {{ $filters.formatedDate(article.created_at) }}
        </td>
        <td class="px-2 py-3">{{ article.claps }}</td>
        <td class="px-2 py-3">{{ article.num_comments }}</td>
      </tr>
    </tbody>
  </table>
</template>
