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
    <table class="w-full" v-if="modelValue.length > 0">
      <thead class="border-y w-full text-gray-400 sticky bg-white top-header">
        <tr>
          <td class="px-2 py-3">Titel</td>
          <td class="px-2 py-3">Datum</td>
          <td class="px-2 py-3">Beiträge</td>
        </tr>
      </thead>
      <tbody>
        <tr v-for="collection in modelValue">
          <td class="px-2 py-3 text-blue-400 font-semibold">
            {{ collection.title }}
          </td>
          <td class="px-2 py-3">
            {{ $filters.formatedDate(collection.created_at) }}
          </td>
          <td class="px-2 py-3">
            {{ collection.num_articles }}
          </td>
        </tr>
      </tbody>
    </table>
    <p class="text-sm pl-2 mb-12" v-else>Keine Ergebnisse…</p>
  </section>
</template>
