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
          <td class="px-2 py-3">geändert</td>
          <td class="px-2 py-3">Typ</td>
          <td class="px-2 py-3">Größe</td>
        </tr>
      </thead>
      <tbody>
        <tr v-for="attachment in modelValue">
          <td class="px-2 py-3 text-green font-semibold">
            <template v-if="attachment.type == 'AttachedUrl'">
              <a :href="attachment.url" target="_blank">
                {{ attachment.title }}
              </a>
            </template>
            <router-link :to="attachment.url" class="cursor-pointer" v-else>
              {{ attachment.title }}
            </router-link>
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
    <p class="text-sm pl-2 mb-12" v-else>Keine Ergebnisse…</p>
  </section>
</template>
