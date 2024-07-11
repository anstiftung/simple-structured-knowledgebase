<script setup>
import { ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { storeToRefs } from 'pinia'
import { useUserStore } from '@/stores/user'
import UserService from '@/services/UserService'

const userStore = useUserStore()
const { hasPermission } = storeToRefs(userStore)
const router = useRouter()
const route = useRoute()
const id = route.params.id
const user = ref()

const loadFromServer = () => {
  let loadFunction = null
  loadFunction = UserService.getUser(id)

  loadFunction
    .then(data => {
      user.value = data
      document.title = `Cowiki | ${user.value.title}`
    })
    .catch(error => {
      router.push({ name: 'not-found' })
    })
}

loadFromServer()
</script>
<template>
  <h1>User-Page: {{ user.name }}</h1>
</template>
