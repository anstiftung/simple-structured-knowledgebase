<script setup>
const props = defineProps({
  collections: Object,
})
</script>
<template>
  <table class="w-full mb-4" v-if="collections.length > 0">
    <thead class="border-y w-full text-gray-400 sticky bg-white top-0">
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
      <tr v-for="collection in collections">
        <td class="px-2 py-3 text-blue-400 font-semibold">
          <div
            class="cursor-pointer flex items-center"
            @click.prevent="$emit('resultSelected', collection)"
          >
            {{ collection.title }}
          </div>
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
</template>
