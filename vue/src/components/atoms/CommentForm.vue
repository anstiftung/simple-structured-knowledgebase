<script setup>
import { reactive, inject } from 'vue'
import { useVuelidate } from '@vuelidate/core'
import { required$, maxLength$ } from '@/plugins/validators.js'
import TextareaWithCounter from './TextareaWithCounter.vue'

import CommentService from '@/services/CommentService'

const $toast = inject('$toast')

const emit = defineEmits(['save'])
const props = defineProps({
  article: {
    type: Object,
    required: true,
  },
})
const formData = reactive({
  comment: {
    content: '',
  },
})

const rules = {
  comment: {
    content: { required$, maxLength: maxLength$(1000) },
  },
}
const v$ = useVuelidate(rules, formData)

const createComment = async () => {
  const formIsCorret = await v$.value.$validate()
  if (!formIsCorret || !props.article) {
    $toast.error('Formular ungültig')
    return
  }
  formData.comment.article_id = props.article.id
  CommentService.createComment(formData.comment).then(data => {
    $toast.success('Kommentar erfolgreich gespeichert')
    formData.comment.content = ''
    v$.value.$reset()
    emit('save')
  })
}
</script>

<template>
  <div class="py-8">
    <h3 class="mb-6">Schreibe einen Kommentar</h3>
    <form v-on:submit.prevent="createComment">
      <textarea-with-counter
        v-model="formData.comment.content"
        @update:modelValue="v$.comment.content.$touch"
        class="w-full p-2 border-2 h-[200px] rounded-md outline-none focus:border-blue"
        :maxlength="v$.comment.content.maxLength.$params.max"
      />
      <div
        class="text-sm text-red"
        v-for="error of v$.comment.content.$errors"
        :key="error.$uid"
      >
        <div>! {{ error.$message }}</div>
      </div>
      <button type="submit" class="mt-6 default-button">
        Kommentar absenden
      </button>
    </form>
  </div>
</template>

<style scoped></style>
