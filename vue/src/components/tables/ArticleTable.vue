<script setup>
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
    <table class="w-full mb-4" v-if="modelValue.length > 0">
      <thead class="sticky w-full text-gray-400 bg-white border-y top-header">
        <tr>
          <SortableHeaderCell
            name="title"
            :sortBy="sortBy"
            :sortOrder="sortOrder"
            @sortChanged="changeSort"
            class="px-2 py-3"
            >Titel</SortableHeaderCell
          >
          <th class="px-2 py-3">Ersteller:in</th>
          <th class="px-2 py-3">Status</th>
          <SortableHeaderCell
            name="created_at"
            :sortBy="sortBy"
            :sortOrder="sortOrder"
            @sortChanged="changeSort"
            class="px-2 py-3"
            >erstellt</SortableHeaderCell
          >
          <SortableHeaderCell
            name="updated_at"
            :sortBy="sortBy"
            :sortOrder="sortOrder"
            @sortChanged="changeSort"
            class="px-2 py-3"
            >geändert</SortableHeaderCell
          >
          <SortableHeaderCell
            name="claps"
            :sortBy="sortBy"
            :sortOrder="sortOrder"
            @sortChanged="changeSort"
            class="px-2 py-3"
            >Claps</SortableHeaderCell
          >
          <th class="px-2 py-3">Kommentare</th>
        </tr>
      </thead>
      <tbody>
        <tr
          v-for="article in modelValue"
          :class="article.deleted_at ? 'opacity-50' : ''"
        >
          <td class="px-2 py-1 font-semibold text-orange">
            <router-link :to="article.url" class="cursor-pointer">
              {{ article.title }}
            </router-link>
            <icon
              name="trash"
              class="text-gray-400"
              v-if="article.deleted_at"
            ></icon>
          </td>
          <td class="px-2 py-3">{{ article.created_by.name }}</td>
          <td class="px-2 py-3">
            <div v-if="article.state.key == 'publish'" title="Veröffentlicht">
              <icon name="book-open-text" class="size-5" />
            </div>
            <div
              v-else-if="article.state.key == 'review'"
              title="Zum Review eingereicht"
            >
              <icon name="book-open-user" class="size-5" />
            </div>
            <div v-else title="nicht Veröffentlicht">
              <icon name="book" class="size-5" />
            </div>
          </td>
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
    <p class="pl-2 mb-12 text-s" v-else>Keine Ergebnisse…</p>
  </section>
</template>
