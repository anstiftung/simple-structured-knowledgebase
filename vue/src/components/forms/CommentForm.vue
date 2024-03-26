<script setup>
import { reactive, inject } from 'vue'
import { useVuelidate } from '@vuelidate/core'
import { required$, maxLength$ } from '@/plugins/validators.js'
import InputWrapper from '@/components/forms/InputWrapper.vue'

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
    <form v-on:submit.prevent="createComment" v-if="formData.comment">
      <input-wrapper
        v-model="formData.comment.content"
        :maxlength="v$.comment.content.maxLength.$params.max"
        :errors="v$.comment.content.$errors"
        label="Kommentarinhalt"
        helpText="LOrem"
      >
        <template #default="{ inputId, modelValue, updateValue }">
          <textarea
            placeholder="Kommentarinhalt"
            class="min-h-[200px]"
            :id="inputId"
            :value="modelValue"
            @input="updateValue"
          ></textarea>
        </template>
      </input-wrapper>

      <button type="submit" class="mt-6 default-button">
        Kommentar absenden
      </button>
    </form>
  </div>
</template>

<style scoped></style>
