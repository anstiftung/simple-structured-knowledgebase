<script setup>
import { ref } from 'vue'

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
      <thead class="sticky w-full text-gray-400 bg-white border-y top-header">
        <tr>
          <td
            class="px-2 py-3 cursor-pointer"
            @click.prevent="changeSort('title')"
          >
            Titel
            <span v-if="sortBy === 'title' && sortOrder == 'asc'"
              ><icon name="arrow-up" />
            </span>
            <span v-if="sortBy === 'title' && sortOrder == 'desc'">
              <icon name="arrow-down" />
            </span>
          </td>
          <td
            class="px-2 py-3 cursor-pointer"
            @click="changeSort('created_at')"
          >
            Datum
            <span v-if="sortBy === 'created_at' && sortOrder == 'asc'"
              ><icon name="arrow-up" />
            </span>
            <span v-if="sortBy === 'created_at' && sortOrder == 'desc'">
              <icon name="arrow-down" />
            </span>
          </td>
          <td
            class="px-2 py-3 cursor-pointer"
            @click="changeSort('updated_at')"
          >
            geändert
          </td>
          <td class="px-2 py-3">Typ</td>
          <td class="px-2 py-3">Größe</td>
        </tr>
      </thead>
      <tbody>
        <tr
          v-for="attachment in modelValue"
          :class="attachment.deleted_at ? 'opacity-50' : ''"
        >
          <td
            class="flex items-center gap-2 px-2 py-3 font-semibold text-green"
          >
            <router-link :to="attachment.url" class="cursor-pointer">
              {{ attachment.title }}
            </router-link>
            <icon
              name="trash"
              class="text-gray-400"
              v-if="attachment.deleted_at"
            ></icon>
          </td>
          <td class="px-2 py-3">
            {{ $filters.formatedDate(attachment.created_at) }}
          </td>
          <td class="px-2 py-3">
            {{ $filters.formatedDate(attachment.updated_at) }}
          </td>
          <td class="px-2 py-3">
            {{ attachment.mime_type ? attachment.mime_type : 'url' }}
          </td>
          <td class="px-2 py-3">
            {{
              attachment.filesize
                ? $filters.bytesToHumandReadableSize(attachment.filesize)
                : ''
            }}
          </td>
        </tr>
      </tbody>
    </table>
    <p class="pl-2 mb-12 text-sm" v-else>Keine Ergebnisse…</p>
  </section>
</template>
