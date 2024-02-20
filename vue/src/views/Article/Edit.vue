<script setup>
import { reactive, computed } from 'vue'
import { storeToRefs } from 'pinia'
import { useToast } from 'vue-toastification'
import { useRouter, useRoute, onBeforeRouteLeave } from 'vue-router'
import { useVuelidate } from '@vuelidate/core'

import { useUserStore } from '@/stores/user'
import ArticleService from '@/services/ArticleService'
import { required$, maxLength$ } from '@/plugins/validators.js'

import Editor from '@/components/editor/Editor.vue'
import StateSelect from '@/components/atoms/StateSelect.vue'
import UserSelect from '@/components/atoms/UserSelect.vue'

import ToastService from '@/services/ToastService'
import ModelHeader from '@/components/layouts/ModelHeader.vue'

const toast = useToast()

const router = useRouter()
const route = useRoute()

const userStore = useUserStore()
const { hasPermission, getUser } = storeToRefs(userStore)

let persistedArticle = ''
const formData = reactive({
  article: {
    title: '',
    description: '',
    content: '',
  },
})

const rules = {
  article: {
    title: { required$, maxLength: maxLength$(255), $autoDirty: true },
    description: { required$, maxLength: maxLength$(1000), $autoDirty: true },
  },
}

const v$ = useVuelidate(rules, formData)

const init = () => {
  // edit mode
  if (route.params.slug) {
    ArticleService.getArticle(route.params.slug).then(data => {
      formData.article = data
      document.title = `Cowiki | ${formData.article.title} Bearbeiten`
      persistedArticle = JSON.stringify(formData.article)
    })
  } else {
    // add the current created_by user to the empty formData.article: this allows showing it in the sidebar in an unsaved state
    formData.article.created_by = getUser
    persistedArticle = JSON.stringify(formData.article)
  }
}
init()

const isDirty = computed(() => {
  return JSON.stringify(formData.article) != persistedArticle
})

onBeforeRouteLeave((to, from, next) => {
  if (isDirty.value) {
    ToastService.confirm(
      'Ungespeicherte Änderungen! Diese Seite wirklich verlassen?',
      next,
    )
  } else {
    next()
  }
})

const persist = async () => {
  const formIsCorret = await v$.value.$validate()
  if (!formIsCorret) {
    toast.error('Formular ungültig')
    return
  }

  const afterPersist = data => {
    formData.article = data
    persistedArticle = JSON.stringify(data)
    toast.success('Beitrag erfolgreich gespeichert')
    router.push(data.url)
  }

  if (formData.article.id) {
    ArticleService.updateArticle(formData.article).then(afterPersist)
  } else {
    ArticleService.createArticle(formData.article).then(afterPersist)
  }
}

const discard = () => {
  if (isDirty.value) {
    ToastService.confirm(
      'Ungespeicherte Änderungen wirklich verwerfen?',
      () => {
        formData.article = JSON.parse(persistedArticle)
      },
    )
  }
}
</script>

<template>
  <section>
    <model-header colorClass="bg-orange" secondaryColorClass="bg-orange/50">
      <template v-slot:description>
        Beitrag {{ formData.article.id ? 'bearbeiten' : 'erstellen' }}
      </template>
      <template v-slot:content>
        <input
          class="w-full text-4xl text-center bg-transparent outline-none placeholder:text-black"
          v-model="formData.article.title"
          autofocus
          placeholder="Titel des Beitrags"
          @update:modelValue="v$.article.title.$touch"
        />
        <div
          class="text-sm text-red"
          v-for="error of v$.article.title.$errors"
          :key="error.$uid"
        >
          <div>! {{ error.$message }}</div>
        </div>
      </template>
    </model-header>
    <div class="grid grid-cols-6 width-wrapper min-h-[70vh]">
      <div class="flex flex-col col-span-4 px-8 py-16 bg-white">
        <textarea
          class="w-full text-xl bg-transparent outline-none"
          v-model="formData.article.description"
          placeholder="Kurzbeschreibung"
          @update:modelValue="v$.article.description.$touch"
        />
        <div
          class="text-sm text-red"
          v-for="error of v$.article.description.$errors"
          :key="error.$uid"
        >
          <div>! {{ error.$message }}</div>
        </div>
        <div class="mt-8 grow">
          <editor v-model="formData.article.content" />
        </div>
      </div>
      <div
        class="flex flex-col justify-between col-span-2 px-8 py-16 bg-gray-100 sticky-sidebar"
      >
        <div class="flex flex-col gap-6 text-sm">
          <div v-if="formData.article.id">
            <h4 class="mb-2 text-sm text-gray-300">Zustand</h4>
            <state-select v-model="formData.article.state"></state-select>
          </div>
          <div v-if="formData.article.created_by">
            <h4 class="mb-2 text-sm text-gray-300">Ersteller*in</h4>
            <user-select
              v-if="
                hasPermission('edit article creator') && formData.article.id
              "
              v-model="formData.article.created_by"
            ></user-select>
            <p v-else>{{ formData.article.created_by.name }}</p>
          </div>
        </div>
        <div class="flex justify-end gap-4">
          <button
            class="secondary-button"
            v-show="formData.article.id"
            @click="discard"
          >
            Verwerfen
          </button>
          <button class="default-button" :disabled="!isDirty" @click="persist">
            Speichern
          </button>
        </div>
      </div>
    </div>
  </section>
</template>

<style scoped></style>
