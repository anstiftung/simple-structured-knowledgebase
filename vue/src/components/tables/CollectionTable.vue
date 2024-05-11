<script setup>
import Icon from '../atoms/Icon.vue'

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
          <td class="px-2 py-3">Ersteller:in</td>
          <td class="px-2 py-3">Status</td>
          <td class="px-2 py-3">erstellt</td>
          <td class="px-2 py-3">geändert</td>
        </tr>
      </thead>
      <tbody>
        <tr v-for="collection in modelValue">
          <td class="px-2 py-3 text-blue-400 font-semibold">
            <router-link :to="collection.url" class="cursor-pointer">
              {{ collection.title }}
            </router-link>
          </td>
          <td class="px-2 py-3">
            {{ $filters.formatedDate(collection.created_at) }}
          </td>
          <td class="px-2 py-3">
            {{ collection.num_articles }}
          </td>
          <td class="px-2 py-3">
            {{ collection.created_by.name }}
          </td>
          <td class="px-2 py-3">
            <div v-if="collection.published" title="Veröffentlicht">
              <icon name="book-open-text" class="size-5" />
            </div>
            <div v-else title="nicht Veröffentlicht">
              <icon name="book" class="size-5" />
            </div>
          </td>
          <td class="px-2 py-3">
            {{ $filters.formatedDate(collection.created_at) }}
          </td>
          <td class="px-2 py-3">
            {{ $filters.formatedDate(collection.updated_at) }}
          </td>
        </tr>
      </tbody>
    </table>
    <p class="text-sm pl-2 mb-12" v-else>Keine Ergebnisse…</p>
  </section>
</template>
