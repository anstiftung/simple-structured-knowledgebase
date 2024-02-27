<script setup>
import { reactive, computed, inject } from 'vue'
import CollectionService from '@/services/CollectionService'
import { useRouter, useRoute, onBeforeRouteLeave } from 'vue-router'
import { useVuelidate } from '@vuelidate/core'
import { required$, maxLength$ } from '@/plugins/validators.js'
import draggable from 'vuedraggable'
import SearchForm from '@/components/SearchForm.vue'
import ItemLine from '../../components/atoms/ItemLine.vue'
import ModelHeader from '@/components/layouts/ModelHeader.vue'
import InputWithCounter from '@/components/atoms/InputWithCounter.vue'
import TextareaWithCounter from '@/components/atoms/TextareaWithCounter.vue'

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
    $toast.error('Formular ungültig')
    return
  }

  const afterPersist = data => {
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
      formData.collection = JSON.parse(persistedCollection)
    })
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
        <input-with-counter
          class="w-full text-4xl text-center bg-transparent outline-none placeholder:text-black"
          v-model="formData.collection.title"
          autofocus
          placeholder="Titel der Sammlung"
          @update:modelValue="v$.collection.title.$touch"
          position="bottom"
          maxlength="255"
        />
        <div
          class="text-sm text-red"
          v-for="error of v$.collection.title.$errors"
          :key="error.$uid"
        >
          <div>! {{ error.$message }}</div>
        </div>
      </template>
    </model-header>
    <div class="grid grid-cols-6 width-wrapper min-h-[70vh]">
      <div class="flex flex-col col-span-4 px-8 py-16 bg-white">
        <textarea-with-counter
          class="w-full mb-4 text-xl bg-transparent outline-none"
          v-model="formData.collection.description"
          placeholder="Kurzbeschreibung"
          @update:modelValue="v$.collection.description.$touch"
          maxlength="1000"
          position="right"
        />
        <div
          class="mb-4 text-sm text-red"
          v-for="error of v$.collection.description.$errors"
          :key="error.$uid"
        >
          <div>! {{ error.$message }}</div>
        </div>
        <div
          class="mb-4"
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
                  :dragable="hasPermission('edit collections')"
                  :show-type="false"
                />
                <div @click="removeArticle(element)">[REMOVE]</div>
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
        class="flex flex-col justify-between col-span-2 px-8 py-16 bg-gray-100 sticky-sidebar"
      >
        <div class="text-sm">
          @todo: Edit creator and state of the collection!
        </div>
        <div class="flex justify-end gap-4">
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
