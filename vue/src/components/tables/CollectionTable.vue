<script setup>
import Icon from '../atoms/Icon.vue'
import SortableHeaderCell from '../atoms/tables/SortableHeaderCell.vue'

const props = defineProps({
  modelValue: {
    type: Array,
    default: [],
  },
  sortBy: {
    required: false,
  },
  sortOrder: {
    required: false,
  },
})

const emit = defineEmits(['sortChanged'])

const changeSort = _sortBy => {
  if (props.sortBy === _sortBy) {
    let order = props.sortOrder === 'asc' ? 'desc' : 'asc'
    emit('sortChanged', { sortBy: _sortBy, sortOrder: order })
  } else {
    emit('sortChanged', { sortBy: _sortBy, sortOrder: 'asc' })
  }
}
</script>
<template>
  <section>
    <table class="w-full" v-if="modelValue.length > 0">
      <thead class="border-y w-full text-gray-400 sticky bg-white top-header">
        <tr>
          <SortableHeaderCell
            name="title"
            :sortBy="sortBy"
            :sortOrder="sortOrder"
            @sortChanged="changeSort"
            class="px-2 py-3 cursor-pointer text-gray-500"
            >Titel</SortableHeaderCell
          >
          <th class="px-2 py-3">Beiträge</th>
          <th class="px-2 py-3">Ersteller:in</th>
          <SortableHeaderCell
            name="published"
            :sortBy="sortBy"
            :sortOrder="sortOrder"
            @sortChanged="changeSort"
            class="px-2 py-3 cursor-pointer text-gray-500"
            >Status</SortableHeaderCell
          >
          <SortableHeaderCell
            name="created_at"
            :sortBy="sortBy"
            :sortOrder="sortOrder"
            @sortChanged="changeSort"
            class="px-2 py-3 cursor-pointer text-gray-500"
            >erstellt</SortableHeaderCell
          >
          <SortableHeaderCell
            name="updated_at"
            :sortBy="sortBy"
            :sortOrder="sortOrder"
            @sortChanged="changeSort"
            class="px-2 py-3 cursor-pointer text-gray-500"
            >geändert</SortableHeaderCell
          >
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
