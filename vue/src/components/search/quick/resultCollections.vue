<script setup>
const props = defineProps({
  collections: Object,
})
</script>
<template>
  <tbody>
    <tr v-for="collection in collections">
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
</template>
