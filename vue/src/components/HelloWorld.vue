<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
defineProps({
  msg: String,
})

const count = ref(0)

onMounted(() => {
  console.log('Hello World!')
  axios.get('/api/recipe').then(resp => {
    console.log(resp.data)
  })
})
</script>

<template>
  <h1>{{ msg }}</h1>

  <div class="card">
    <button type="button" @click="count++">count is {{ count }}</button>
  </div>
</template>
