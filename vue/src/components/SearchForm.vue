<script setup>
import { ref, onMounted } from 'vue'
import { useRouter, useRoute } from 'vue-router'
const router = useRouter()
const route = useRoute()

const props = defineProps({
  placeholder: String,
})
const searchQuery = ref('')

onMounted(() => {
  if (route.name === 'search' && route.params.query) {
    searchQuery.value = route.params.query
  }
})

const runSearch = () => {
  router.push({ name: 'search', params: { query: searchQuery.value } })
}
</script>

<template>
  <form
    class="flex gap-2 px-4 py-2 bg-white rounded-md drop-shadow-lg"
    v-on:submit.prevent="runSearch()"
  >
    <input
      class="w-full outline-none placeholder:text-gray-200"
      type="text"
      :placeholder="placeholder"
      v-model="searchQuery"
    />
    <svg
      role="button"
      xmlns="http://www.w3.org/2000/svg"
      width="24"
      class="fill-gray-800"
      height="24"
      viewBox="0 0 24 24"
      @click.prevent="runSearch()"
    >
      <path
        d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"
      />
    </svg>
  </form>
</template>

<style scoped></style>
