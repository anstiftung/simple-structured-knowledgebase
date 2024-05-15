<script setup>
import { reactive, computed, inject } from 'vue'
import CollectionService from '@/services/CollectionService'
import { useRouter, useRoute, onBeforeRouteLeave } from 'vue-router'
import { useVuelidate } from '@vuelidate/core'
import { required$, maxLength$ } from '@/plugins/validators.js'
import draggable from 'vuedraggable'
import SearchForm from '@/components/forms/SearchForm.vue'
import ItemLine from '@/components/atoms/ItemLine.vue'
import ModelHeader from '@/components/layouts/ModelHeader.vue'
import InputWrapper from '@/components/forms/InputWrapper.vue'
import UserSelect from '@/components/forms/UserSelect.vue'

import { useUserStore } from '@/stores/user'
import { storeToRefs } from 'pinia'

// If you need UserPermissions, you'll need the next three lines
const userStore = useUserStore()
const { hasPermission } = storeToRefs(userStore)

const $toast = inject('$toast')
const router = useRouter()
const route = useRoute()

let persistedCollection = ''
const formData = reactive({
  collection: {
    title: '',
    description: '',
    published: false,
    articles: [],
  },
})

const rules = {
  collection: {
    title: { required$, maxLength: maxLength$(255), $autoDirty: true },
    description: { required$, maxLength: maxLength$(1000), $autoDirty: true },
  },
}

const v$ = useVuelidate(rules, formData)

const init = () => {
  // edit mode
  if (route.params.slug) {
    CollectionService.getCollection(route.params.slug).then(data => {
      formData.collection = data
      document.title = `Cowiki | ${formData.collection.title} Bearbeiten`
      persistedCollection = JSON.stringify(formData.collection)
    })
  } else {
    persistedCollection = JSON.stringify(formData.collection)
  }
}
init()

const selectModel = model => {
  model.order = formData.collection.articles.length
  formData.collection.articles.push(model)
}

const removeArticle = article => {
  formData.collection.articles = formData.collection.articles.filter(function (
    a,
  ) {
    return a.id != article.id
  })
}

const sortCallback = event => {
  // make order-property consistent with sorting
  let i = 0
  formData.collection.articles.map(element => {
    element.order = i
    i++
  })
}

const isDirty = computed(() => {
  return JSON.stringify(formData.collection) != persistedCollection
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
    $toast.error('Hier fehlt etwas. Bitte prüfe alle Felder')
    return
  }

  const afterPersist = data => {
    // only on update we warn about a slug changed caused by the `HasUniqueSlugTrait^`
    if (formData.collection.id && formData.collection.slug != data.slug) {
      $toast.warning(
        'Der Slug enthielt ungültige Zeichen oder wurde bereits verwendet. Wir haben ihn leicht angepasst.',
      )
    }
    formData.collection = data
    persistedCollection = JSON.stringify(data)
    $toast.success('Sammlung erfolgreich gespeichert')
    router.push(data.url)
  }
  if (formData.collection.id) {
    CollectionService.updateCollection(formData.collection).then(afterPersist)
  } else {
    CollectionService.createCollection(formData.collection).then(afterPersist)
  }
}

const discard = () => {
  if (isDirty.value) {
    $toast.confirm('Ungespeicherte Änderungen wirklich verwerfen?', () => {
      router.push(formData.collection.url)
    })
  } else {
    router.push(formData.collection.url)
  }
}
</script>

<template>
  <section>
    <model-header colorClass="bg-blue-400" secondaryColorClass="bg-blue-400/50">
      <template v-slot:description>
        Sammlung {{ formData.collection.id ? 'bearbeiten' : 'erstellen' }}
      </template>
      <template v-slot:content>
        <h2 class="text-4xl text-center">
          <span v-if="formData.collection.title">
            {{ formData.collection.title }}
          </span>
          <span class="opacity-70" v-else>Sammlung erstellen</span>
        </h2>
      </template>
    </model-header>
    <div class="grid grid-cols-6 width-wrapper min-h-[70vh]">
      <div class="flex flex-col col-span-4 gap-6 px-8 py-16 bg-white">
        <input-wrapper
          v-model="formData.collection.title"
          @update:modelValue="v$.collection.title.$touch"
          :maxlength="v$.collection.title.maxLength.$params.max"
          :errors="v$.collection.title.$errors"
          label="Titel"
          helpText="Der Titel sollte prägnant sein. Er wird bei der Suche nach Sammlungen verwendet."
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
          v-model="formData.collection.description"
          :maxlength="v$.collection.description.maxLength.$params.max"
          :errors="v$.collection.description.$errors"
          label="Beschreibung"
          helpText="Die Beschreibung gibt eine grobe Information welches Themengebiet innerhalb der Sammlung abgedeckt wird."
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

        <div
          v-if="
            formData.collection.articles &&
            formData.collection.articles.length > 0
          "
        >
          <h3 class="mb-2 text-lg">
            <template v-if="formData.collection.articles.length == 1"
              >Verknüpfter Beitrag</template
            >
            <template v-else>Verknüpfte Beiträge</template>
          </h3>
          <draggable
            v-model="formData.collection.articles"
            group="articles"
            @change="sortCallback"
            item-key="id"
          >
            <template #item="{ element }">
              <div class="flex justify-between">
                <item-line
                  :model="element"
                  class="mb-2"
                  :dragable="hasPermission('update collections')"
                  :show-type="false"
                />
                <a
                  @click="removeArticle(element)"
                  title="Von Startseite entfernen"
                  class="cursor-pointer"
                >
                  <icon name="trash" class="text-gray-400 size-5" />
                </a>
              </div>
            </template>
          </draggable>
        </div>
        <div class="mb-4" v-if="formData.collection.articles">
          <h3 class="mb-2 text-lg">Verknüpften Beitrag hinzufügen</h3>
          <search-form
            placeholder="Suche in meinen Beiträgen, Anhängen und Sammlungen"
            class="grow"
            @selected="selectModel"
            :navigate="false"
            :types="['articles']"
          />
        </div>
      </div>
      <div
        class="flex flex-col justify-between col-span-2 px-8 py-16 bg-gray-100 sticky-sidebar max-h-full-without-header"
      >
        <div class="flex flex-col gap-6 text-sm">
          <div>
            <h4 class="mb-2 text-sm text-gray-300">Zustand</h4>
            <select
              v-model="formData.collection.published"
              class="w-full max-w-xl px-4 py-3 text-gray-800 rounded-md"
            >
              <option :value="false">Nicht Veröffentlicht</option>
              <option :value="true">Veröffentlicht</option>
            </select>
          </div>
          <div v-if="formData.collection.id">
            <h4 class="mb-2 text-sm text-gray-300">Startseite</h4>
            <input
              :disabled="
                !hasPermission('feature collections') ||
                !formData.collection.published
              "
              type="checkbox"
              id="featured"
              v-model="formData.collection.featured"
            />
            <label
              for="featured"
              :class="[
                'inline-block ml-4',
                [formData.collection.published ? '' : 'text-gray-400'],
              ]"
              >Sammlung auf Startseite anzeigen</label
            >
          </div>
          <div v-if="formData.collection.created_by">
            <h4 class="mb-2 text-sm text-gray-300">Ersteller*in</h4>
            <user-select
              :onlyShowEditors="true"
              v-if="
                hasPermission('edit collection creator') &&
                formData.collection.id
              "
              v-model="formData.collection.created_by"
            ></user-select>
            <p v-else>{{ formData.collection.created_by.name }}</p>
          </div>
          <div
            v-if="
              formData.collection.id && hasPermission('edit collection slug')
            "
          >
            <input-wrapper
              v-model="formData.collection.slug"
              label="Slug"
              helpText="Der Slug ist eine eindeutige Zeichenkette, die die URL der Sammlung bildet."
            >
              <template #default="{ inputId, modelValue, updateValue }">
                <input
                  type="text"
                  autofocus
                  placeholder="Slug der Sammlung"
                  :id="inputId"
                  :value="modelValue"
                  @input="updateValue"
                />
              </template>
            </input-wrapper>
          </div>
        </div>
        <div class="flex justify-between gap-4">
          <button
            class="secondary-button"
            v-show="formData.collection.id"
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
