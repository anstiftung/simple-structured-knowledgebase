<script setup>
import { reactive, computed } from 'vue'
import CollectionService from '@/services/CollectionService'
import { useToast } from 'vue-toastification'
import { useRouter, useRoute, onBeforeRouteLeave } from 'vue-router'
import { useVuelidate } from '@vuelidate/core'
import { required$, maxLength$ } from '@/plugins/validators.js'
import ConfirmationToast from '@/components/atoms/ConfirmationToast.vue'
import Editor from '@/components/fields/Editor.vue'

const toast = useToast()
const router = useRouter()
const route = useRoute()

let persistedCollection = ''
const formData = reactive({
  collection: {
    title: '',
    description: '',
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
    router.push({ name: 'collection', params: { slug: data.slug } })
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
    <div
      class="px-12 py-8 header-clip width-wrapper rounded-[20px] bg-blue-400"
    >
      <div class="text-center">
        <input
          class="text-xl bg-transparent outline-none"
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
          class="w-full text-xl bg-transparent outline-none"
          v-model="formData.collection.description"
          placeholder="Kurzbeschreibung"
          @update:modelValue="v$.collection.description.$touch"
        />
        <div
          class="text-sm text-red"
          v-for="error of v$.collection.description.$errors"
          :key="error.$uid"
        >
          <div>! {{ error.$message }}</div>
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

<style scoped>
.header-clip {
  /* clip-path: polygon(0px 0px, 0 80%, 100% 100%, 100% 0px); */
}
</style>
