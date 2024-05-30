<script setup>
defineEmits(['resultSelected'])

const props = defineProps({
  articles: Object,
})
</script>
<template>
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
</template>
