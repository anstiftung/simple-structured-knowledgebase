<script setup>
import { reactive, onMounted } from 'vue'
import ArticleService from '@/services/ArticleService'
import { useToast } from 'vue-toastification'
import { useRouter, onBeforeRouteLeave } from 'vue-router'
import { useVuelidate } from '@vuelidate/core'
import { required, maxLength } from '@vuelidate/validators'

const toast = useToast()
const router = useRouter()

let persistedArticle = ''
const article = reactive({
  title: '',
  description: '',
})

const rules = {
  title: { required, maxLength: maxLength(100), $autoDirty: true },
  description: { required, maxLength: maxLength(1000), $autoDirty: true },
}
const v$ = useVuelidate(rules, article)

onMounted(() => {
  persistedArticle = JSON.stringify(article)
})

const unsavedChanges = () => {
  return JSON.stringify(article) != persistedArticle
}

onBeforeRouteLeave((to, from) => {
  if (unsavedChanges()) {
    const answer = window.confirm(
      'Ungespeicherte Änderungen! Diese Seite wirklich verlassen?',
    )
    if (!answer) return false
  }
})

const persist = async () => {
  const formIsCorret = await v$.value.$validate()
  if (!formIsCorret) {
    toast.error('Formular ungültig')
    return
  }
  ArticleService.createArticle(article).then(data => {
    // updates persisted article
    persistedArticle = JSON.stringify(article)
    toast.success('Beitrag erfolgreich gespeichert')
    router.push({ name: 'article', params: { slug: data.slug } })
  })
}
</script>

<template>
  <section class="bg-orange/50">
    <div class="px-12 py-8 header-clip width-wrapper rounded-[20px] bg-orange">
      <div class="text-center">
        <input
          class="text-xl bg-transparent outline-none"
          v-model="article.title"
          autofocus
          placeholder="Titel des neuen Eintrags"
          @update:modelValue="v$.formData.title.$touch"
        />
        <div
          class="text-sm text-red"
          v-for="error of v$.title.$errors"
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
          v-model="article.description"
          placeholder="Kurzbeschreibung"
          @update:modelValue="v$.formData.description.$touch"
        />
        <div
          class="text-sm text-red"
          v-for="error of v$.description.$errors"
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
          <button class="secondary-button" v-if="false">Verwerfen</button>
          <button class="default-button" @click="persist">Speichern</button>
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
