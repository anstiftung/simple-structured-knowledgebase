<script setup>
defineProps({
  modelValue: {
    type: Array,
    default: [],
  },
})
</script>
<template>
  <section>
    <table class="w-full mb-4" v-if="modelValue.length > 0">
      <thead class="border-y w-full text-gray-400">
        <tr>
          <td class="px-2 py-3">Titel</td>
          <td class="px-2 py-3">Ersteller:in</td>
          <td class="px-2 py-3">Veröffentlicht</td>
          <td class="px-2 py-3">geändert</td>
          <td class="px-2 py-3">Claps</td>
          <td class="px-2 py-3">Kommentare</td>
        </tr>
      </thead>
      <tbody>
        <tr v-for="article in modelValue">
          <td class="px-2 py-1 text-orange font-semibold">
            {{ article.title }}
          </td>
          <td class="px-2 py-3">{{ article.created_by.name }}</td>
          <td class="px-2 py-3">
            {{ $filters.formatedDate(article.created_at) }}
          </td>
          <td class="px-2 py-3">
            {{ $filters.formatedDate(article.updated_at) }}
          </td>
          <td class="px-2 py-3">{{ article.claps }}</td>
          <td class="px-2 py-3">{{ article.num_comments }}</td>
        </tr>
      </tbody>
    </table>
    <p class="text-sm" v-else>Keine Ergebnisse…</p>
  </section>
</template>
