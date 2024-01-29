<script setup>
import { ref, reactive, computed } from 'vue'
import CollectionService from '@/services/CollectionService'
import SearchService from '@/services/SearchService'
import { useToast } from 'vue-toastification'
import { useRouter, useRoute, onBeforeRouteLeave } from 'vue-router'
import { useVuelidate } from '@vuelidate/core'
import { required$, maxLength$ } from '@/plugins/validators.js'
import ConfirmationToast from '@/components/atoms/ConfirmationToast.vue'
import ItemLine from '@/components/atoms/ItemLine.vue'
import draggable from 'vuedraggable'
import { useDebounceFn } from '@vueuse/core'

import { useUserStore } from '@/stores/user'
import { storeToRefs } from 'pinia'

// If you need UserPermissions, you'll need the next three lines
const userStore = useUserStore()
const { hasPermission } = storeToRefs(userStore)

const toast = useToast()
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
const searchQuery = ref('')
const searchResults = ref([])
const searchMeta = ref(null)
const searchInput = ref(null)

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

const onQueryInput = useDebounceFn(() => {
  searchResults.value = []
  searchMeta.value = null
  querySearch()
}, 250)

const querySearch = () => {
  if (!searchQuery.value || searchQuery.value.length <= 3) {
    return
  }
  SearchService.search(searchQuery.value).then(({ data, meta }) => {
    searchResults.value = data
    searchMeta.value = meta
  })
}

const modelLabel = 'Beitrag'

const modelResults = computed(() => {
  if (searchResults.value.articles) {
    return searchResults.value.articles
  }
})

const selectModel = model => {
  model.order = formData.collection.articles.length
  formData.collection.articles.push(model)
  searchResults.value.articles = searchResults.value.articles.filter(function (
    item,
  ) {
    return item.id !== model.id
  })
}

const sortCallback = event => {
  // make order-property consistent with sorting
  let i = 0
  formData.collection.articles.map(element => {
    element.order = i
    i++
  })

  console.log(formData.collection.articles)
}

const isDirty = computed(() => {
  return JSON.stringify(formData.collection) != persistedCollection
})

onBeforeRouteLeave((to, from, next) => {
  if (isDirty.value) {
    toast.clear()
    const content = {
      component: ConfirmationToast,
      props: {
        message: 'Ungespeicherte Änderungen! Diese Seite wirklich verlassen?',
      },
      listeners: {
        granted: () => next(),
      },
    }
    toast(content, {
      timeout: false,
      icon: false,
      closeButton: false,
    })
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
    formData.collection = data
    persistedCollection = JSON.stringify(data)
    toast.success('Sammlung erfolgreich gespeichert')
    router.push(collection.url)
  }

  if (formData.collection.id) {
    CollectionService.updateCollection(formData.collection).then(afterPersist)
  } else {
    CollectionService.createCollection(formData.collection).then(afterPersist)
  }
}

const discard = () => {
  if (isDirty.value) {
    toast.clear()
    const content = {
      component: ConfirmationToast,
      props: {
        message: 'Ungespeicherte Änderungen wirklich verwerfen?',
      },
      listeners: {
        granted: () => {
          formData.collection = JSON.parse(persistedCollection)
        },
      },
    }
    toast(content, {
      timeout: false,
      icon: false,
      closeButton: false,
    })
  }
}
</script>

<template>
  <section class="bg-orange/50">
    <div class="bg-blue-400 header-clip">
      <div class="py-12 text-center text-white width-wrapper">
        <h3 class="mb-2 font-normal text-center opacity-70">
          Sammlung {{ formData.collection.id ? 'bearbeiten' : 'erstellen' }}
        </h3>
        <input
          class="w-full text-4xl text-center bg-transparent outline-none"
          v-model="formData.collection.title"
          autofocus
          placeholder="Titel des neuen Eintrags"
          @update:modelValue="v$.collection.title.$touch"
        />
        <div
          class="text-sm text-red"
          v-for="error of v$.collection.title.$errors"
          :key="error.$uid"
        >
          <div>! {{ error.$message }}</div>
        </div>
      </div>
    </div>
    <div class="grid grid-cols-6 width-wrapper min-h-[70vh]">
      <div class="flex flex-col col-span-4 px-8 py-16 bg-white">
        <textarea
          class="w-full text-xl bg-transparent outline-none mb-4"
          v-model="formData.collection.description"
          placeholder="Kurzbeschreibung"
          @update:modelValue="v$.collection.description.$touch"
        />
        <div
          class="text-sm text-red mb-4"
          v-for="error of v$.collection.description.$errors"
          :key="error.$uid"
        >
          <div>! {{ error.$message }}</div>
        </div>
        <div class="mb-4" v-if="formData.collection.articles">
          <h3 class="text-lg mb-2">Verknüpfte Beiträge</h3>
          <draggable
            v-model="formData.collection.articles"
            group="articles"
            @change="sortCallback"
            item-key="id"
          >
            <template #item="{ element }">
              <item-line
                :model="element"
                class="mb-2"
                :dragable="hasPermission('edit collections')"
                :show-type="false"
              />
            </template>
          </draggable>
        </div>
        <div class="mb-4" v-if="formData.collection.articles">
          <h3 class="text-lg mb-2">Verknüpften Beitrag hinzufügen</h3>
          <div class="relative overflow-visible bg-white rounded-md">
            <form
              class="flex gap-2 px-4 py-2"
              v-on:submit.prevent="querySearch()"
            >
              <img
                role="button"
                src="/icons/search.svg"
                @click.prevent="querySearch()"
              />
              <input
                class="w-full outline-none placeholder:text-gray-200"
                type="text"
                placeholder="Suchen"
                v-model="searchQuery"
                @input="onQueryInput"
                ref="searchInput"
              />
            </form>
            <div
              class="min-h-[100px] max-h-[400px] overflow-y-scroll bg-white p-4"
            >
              <p
                v-if="!searchMeta || searchMeta.num_results == 0"
                class="mt-8 text-center text-gray-300"
              >
                Keine Ergebnisse
              </p>
              <div v-else class="flex flex-col gap-2">
                <p class="text-sm italic text-gray-300">{{ modelLabel }}</p>
                <item-line
                  v-for="item in modelResults"
                  :model="item"
                  :showType="false"
                  :navigate="false"
                  @click.prevent="selectModel(item)"
                />
              </div>
            </div>
          </div>
        </div>
      </div>
      <div
        class="flex flex-col justify-between col-span-2 px-8 py-16 bg-gray-100"
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
