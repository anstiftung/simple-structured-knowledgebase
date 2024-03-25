<script setup>
import { reactive, computed, inject } from 'vue'
import { storeToRefs } from 'pinia'
import { useRouter, useRoute, onBeforeRouteLeave } from 'vue-router'
import { useVuelidate } from '@vuelidate/core'

import { useUserStore } from '@/stores/user'
import ArticleService from '@/services/ArticleService'
import { required$, maxLength$ } from '@/plugins/validators.js'

import Editor from '@/components/editor/Editor.vue'
import StateSelect from '@/components/forms/StateSelect.vue'
import UserSelect from '@/components/forms/UserSelect.vue'
import InputWithCounter from '@/components/forms/InputWithCounter.vue'
import TextareaWithCounter from '@/components/forms/TextareaWithCounter.vue'

import ModelHeader from '@/components/layouts/ModelHeader.vue'

const $toast = inject('$toast')

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
    $toast.confirm(
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
    $toast.error('Formular ungültig')
    return
  }

  const afterPersist = data => {
    formData.article = data
    persistedArticle = JSON.stringify(data)
    $toast.success('Beitrag erfolgreich gespeichert')
    router.push(data.url)
  }

  if (formData.article.id) {
    ArticleService.updateArticle(formData.article).then(afterPersist)
  } else {
    ArticleService.createArticle(formData.article).then(afterPersist)
  }
}

const deleteArticle = () => {
  $toast.confirm('Artikel wirklich löschen?', () => {
    ArticleService.deleteArticle(formData.article).then(data => {
      router.push({ name: 'dashboard' })
    })
  })
}

const discard = () => {
  if (isDirty.value) {
    $toast.confirm('Ungespeicherte Änderungen wirklich verwerfen?', () => {
      formData.article = JSON.parse(persistedArticle)
    })
  }
}
</script>

<template>
  <section>
    <model-header colorClass="bg-orange" secondaryColorClass="bg-orange/50">
      <template v-slot:description>Beitrag</template>
      <template v-slot:content>
        <h2 class="text-4xl text-center">
          <span v-if="formData.article.title">
            {{ formData.article.title }}
          </span>
          <span class="opacity-70" v-else> Beitrag erstellen </span>
        </h2>
      </template>
    </model-header>
    <div class="grid grid-cols-6 width-wrapper min-h-[70vh]">
      <div class="flex flex-col col-span-4 gap-4 px-8 py-16 bg-white">
        <div>
          <label class="inline-block mb-1 text-sm text-gray-300" for="title"
            >Titel</label
          >
          <input-with-counter
            class="w-full px-2 py-4 border rounded-md outline-none"
            v-model="formData.article.title"
            autofocus
            id="title"
            placeholder="Titel des Beitrags"
            @update:modelValue="v$.article.title.$touch"
            :maxlength="v$.article.title.maxLength.$params.max"
          />
          <div
            class="text-sm text-red"
            v-for="error of v$.article.title.$errors"
            :key="error.$uid"
          >
            <div>! {{ error.$message }}</div>
          </div>
        </div>
        <div>
          <label
            class="inline-block mb-1 text-sm text-gray-300"
            for="description"
            >Beschreibung</label
          >
          <textarea-with-counter
            class="w-full px-2 py-4 bg-transparent border rounded-md"
            v-model="formData.article.description"
            id="description"
            placeholder="Kurzbeschreibung"
            @update:modelValue="v$.article.description.$touch"
            :maxlength="v$.article.description.maxLength.$params.max"
            position="right"
          />
          <div
            class="text-sm text-red"
            v-for="error of v$.article.description.$errors"
            :key="error.$uid"
          >
            <div>! {{ error.$message }}</div>
          </div>
        </div>
        <div>
          <label class="inline-block mb-1 text-sm text-gray-300"
            >Inhaltstext</label
          >
          <div class="px-2 -pt-[1px] pb-4 border rounded-md grow">
            <editor v-model="formData.article.content" />
          </div>
        </div>
      </div>
      <div
        class="flex flex-col justify-between col-span-2 px-8 py-16 bg-gray-100 sticky-sidebar max-h-full-without-header"
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
          <div v-if="hasPermission('approve content') && formData.article.id">
            <h4 class="mb-2 text-sm text-gray-300">Geprüft</h4>
            <input type="checkbox" v-model="formData.article.approved" />
            <span class="inline-block ml-4"
              >Vom Legal Team geprüfter Artikel</span
            >
          </div>
        </div>
        <div class="justify-end">
          <div
            class="mb-4 cursor-pointer"
            v-if="hasPermission('delete articles')"
            @click="deleteArticle"
          >
            <icon name="trash" class="text-black" />
            <span class="inline-block ml-2 underline">Löschen</span>
          </div>
          <div class="flex gap-4">
            <button
              class="secondary-button"
              v-show="formData.article.id"
              @click="discard"
            >
              Verwerfen
            </button>
            <button
              class="default-button"
              :disabled="!isDirty"
              @click="persist"
            >
              Speichern
            </button>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<style scoped></style>
