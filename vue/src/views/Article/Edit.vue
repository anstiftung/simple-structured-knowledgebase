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
import InputWrapper from '@/components/forms/InputWrapper.vue'

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
      router.push(formData.article.url)
    })
  } else {
    router.push(formData.article.url)
  }
}
</script>

<template>
  <section>
    <model-header colorClass="bg-orange" secondaryColorClass="bg-orange/50">
      <template v-slot:description>Beitrag</template>
      <template v-slot:content>
        <h2 class="text-4xl text-center break-words hyphens-auto" lang="de">
          <span v-if="formData.article.title">
            {{ formData.article.title }}
          </span>
          <span class="opacity-70" v-else>Beitrag erstellen</span>
        </h2>
      </template>
    </model-header>
    <div class="grid grid-cols-6 width-wrapper min-h-[70vh]">
      <div class="flex flex-col col-span-4 gap-6 px-8 py-16 bg-white">
        <input-wrapper
          v-model="formData.article.title"
          @update:modelValue="v$.article.title.$touch"
          :maxlength="v$.article.title.maxLength.$params.max"
          :errors="v$.article.title.$errors"
          label="Titel"
          helpText="Der Titel sollte prägnant sein. Er wird bei der Suche nach Artikeln verwendet."
        >
          <template #default="{ inputId, modelValue, updateValue }">
            <input
              type="text"
              autofocus
              placeholder="Titel des Beitrags"
              :id="inputId"
              :value="modelValue"
              @input="updateValue"
            />
          </template>
        </input-wrapper>
        <input-wrapper
          v-model="formData.article.description"
          :maxlength="v$.article.description.maxLength.$params.max"
          :errors="v$.article.description.$errors"
          label="Beschreibung"
          helpText="Die Kurzbeschreibung wird in der Artikelvorschau angezeigt. Sie sollte möglichst kurz den Inhalt des Artikels beschreiben."
        >
          <template #default="{ inputId, modelValue, updateValue }">
            <textarea
              placeholder="Kurzbeschreibung"
              :id="inputId"
              :value="modelValue"
              @input="updateValue"
            >
            </textarea>
          </template>
        </input-wrapper>

        <input-wrapper
          v-model="formData.article.content"
          label="Beitragstext"
          helpText="Hier kannst du den gesamten Inhalt deines Artikels eintragen. Es ist möglich Bilder, sowie Links zu anderen Inhaltstypen einzufügen. Beachte: Um Links zu externen Webseiten zu erstellen, musst du diese zuerst als Anhang zur Bibliothek hinzufügen."
          class="grow"
        >
          <template #default="{ inputId, modelValue, updateValue }">
            <editor
              :id="inputId"
              v-model="formData.article.content"
              class="-mt-4"
            />
          </template>
        </input-wrapper>
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
            <input
              type="checkbox"
              v-model="formData.article.approved"
              id="approved"
            />
            <label class="inline-block ml-4" for="approved"
              >Vom Legal Team geprüfter Artikel</label
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
          <div class="flex justify-between gap-4">
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
