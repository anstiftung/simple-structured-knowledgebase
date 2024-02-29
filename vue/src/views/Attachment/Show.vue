<script setup>
import { ref } from 'vue'
import AttachmentService from '@/services/AttachmentService'
import ArticleCard from '@/components/ArticleCard.vue'
import ModelHeader from '@/components/layouts/ModelHeader.vue'
import Separator from '@/components/layouts/Separator.vue'

import { useRoute, useRouter } from 'vue-router'

const router = useRouter()
const route = useRoute()
const id = route.params.id
const attachedFile = ref(null)

const loadFromServer = () => {
  AttachmentService.getAttachedFile(id, true)
    .then(data => {
      attachedFile.value = data
      document.title = `Cowiki | ${attachedFile.value.title}`
    })
    .catch(error => {
      router.push({ name: 'not-found' })
    })
}

loadFromServer()
</script>

<template>
  <div>
    <model-header
      colorClass="bg-green"
      secondaryColorClass="bg-green/50"
      v-if="attachedFile"
    >
      <template v-slot:description>Anhang</template>
      <template v-slot:content>
        <h2 class="text-4xl text-center">{{ attachedFile.title }}</h2>
      </template>
    </model-header>
    <section v-if="attachedFile" class="grid grid-cols-6 my-8 width-wrapper">
      <div class="col-span-4 px-8 py-8">
        <img
          v-if="attachedFile.is_image"
          :src="attachedFile.serve_url"
          class="w-full h-auto"
        />
        <div
          v-else
          class="px-8 py-12 bg-gray-100 border-[1px] flex flex-col items-center justify-center gap-4 text-center border-gray-400 border-dashed rounded-md"
        >
          <icon name="file" class="text-gray-400 size-20"></icon>
          <p>Keine Vorschau für diesen Dateityp</p>
        </div>
        <h4 class="mt-6">Beschreibung:</h4>
        <p>{{ attachedFile.description }}</p>
      </div>
      <div class="self-start col-span-2 px-8 py-8 border-l sticky-sidebar">
        <div class="flex flex-col gap-8">
          <div v-if="attachedFile.approved">
            <icon name="approved" class="mr-2 text-green size-6"></icon>
            geprüfter Inhalt
          </div>
          <div>
            <h4 class="mb-2 text-sm text-gray-300">Ersteller*in</h4>
            <p>{{ attachedFile.created_by.name }}</p>
          </div>
          <div>
            <h4 class="mb-2 text-sm text-gray-300">erstellt am</h4>
            <p>{{ $filters.formatedDate(attachedFile.created_at) }}</p>
          </div>
          <div>
            <h4 class="mb-2 text-sm text-gray-300">Lizenz</h4>
            <p>{{ attachedFile.license.title }}</p>
          </div>
          <div>
            <h4 class="mb-2 text-sm text-gray-300">Quelle</h4>
            <p>{{ attachedFile.source }}</p>
          </div>
          <div class="grid grid-cols-2">
            <div>
              <h4 class="mb-2 text-sm text-gray-300">Typ</h4>
              <p>{{ $filters.fileNameToFileType(attachedFile.filename) }}</p>
            </div>
            <div>
              <h4 class="mb-2 text-sm text-gray-300">Größe</h4>
              <p>
                {{ $filters.bytesToHumandReadableSize(attachedFile.filesize) }}
              </p>
            </div>
          </div>
          <a
            target="blank"
            :href="attachedFile.serve_url"
            class="text-center default-button"
            >Download</a
          >
        </div>
      </div>
    </section>
    <section class="my-28 width-wrapper" v-if="attachedFile">
      <separator>Enthalten in folgenden Beiträgen</separator>
      <div
        class="grid grid-cols-4 gap-4"
        v-if="attachedFile.articles && attachedFile.articles.length"
      >
        <article-card
          v-for="article in attachedFile.articles"
          :article="article"
        />
      </div>
      <div v-else class="text-sm text-center">
        Der Anhang wird von keinem Beitrag verwendet.
      </div>
    </section>
  </div>
</template>

<style scoped></style>
