<script setup>
import { reactive, computed } from 'vue'
import ArticleService from '@/services/ArticleService'
import { useToast } from 'vue-toastification'
import { useRouter, useRoute, onBeforeRouteLeave } from 'vue-router'
import { useVuelidate } from '@vuelidate/core'
import { required, maxLength } from '@vuelidate/validators'
import ConfirmationToast from '@/components/atoms/ConfirmationToast.vue'

const toast = useToast()
const router = useRouter()
const route = useRoute()

let persistedArticle = ''
const formData = reactive({
  article: {
    title: '',
    description: '',
  },
})

const rules = {
  article: {
    title: { required, maxLength: maxLength(255), $autoDirty: true },
    description: { required, maxLength: maxLength(1000), $autoDirty: true },
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
    persistedArticle = JSON.stringify(formData.article)
  }
}
init()

const isDirty = computed(() => {
  return JSON.stringify(formData.article) != persistedArticle
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
        'allow-route-change': () => next(),
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
    formData.article = data
    persistedArticle = JSON.stringify(data)
    toast.success('Beitrag erfolgreich gespeichert')
    router.push({ name: 'article', params: { slug: data.slug } })
  }

  if (formData.article.id) {
    ArticleService.updateArticle(formData.article).then(afterPersist)
  } else {
    ArticleService.createArticle(formData.article).then(afterPersist)
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
        'allow-route-change': () => {
          formData.article = JSON.parse(persistedArticle)
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
    <div class="px-12 py-8 header-clip width-wrapper rounded-[20px] bg-orange">
      <div class="text-center">
        <input
          class="text-xl bg-transparent outline-none"
          v-model="formData.article.title"
          autofocus
          placeholder="Titel des neuen Eintrags"
          @update:modelValue="v$.article.title.$touch"
        />
        <div
          class="text-sm text-red"
          v-for="error of v$.article.title.$errors"
          :key="error.$uid"
        >
          <div>! {{ error.$message }}</div>
        </div>
      </div>
    </div>
    <div class="grid grid-cols-6 width-wrapper min-h-[70vh]">
      <div class="col-span-4 px-8 py-16 bg-white">
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
        <p class="mt-8">@todo: Editor!</p>
      </div>
      <div
        class="flex flex-col justify-between col-span-2 px-8 py-16 bg-gray-100"
      >
        <div class="text-sm">@todo: Edit creator and state of the article!</div>
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

<style scoped>
.header-clip {
  /* clip-path: polygon(0px 0px, 0 80%, 100% 100%, 100% 0px); */
}
</style>
