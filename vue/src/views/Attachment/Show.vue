<script setup>
import { ref, inject } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { storeToRefs } from 'pinia'
import { useUserStore } from '@/stores/user'

import AttachmentService from '@/services/AttachmentService'
import ArticleCard from '@/components/atoms/ArticleCard.vue'
import ModelHeader from '@/components/layouts/ModelHeader.vue'
import Separator from '@/components/layouts/Separator.vue'

const userStore = useUserStore()
const { hasPermission } = storeToRefs(userStore)
const $toast = inject('$toast')

const router = useRouter()
const route = useRoute()
const id = route.params.id
const attachment = ref(null)

const loadFromServer = () => {
  let getPromise
  if (route.name == 'attachedFile') {
    getPromise = AttachmentService.getAttachedFile(id, true)
  } else if (route.name == 'attachedUrl') {
    getPromise = AttachmentService.getAttachedUrl(id, true)
  }
  getPromise
    .then(data => {
      attachment.value = data
      document.title = `Cowiki | ${attachment.value.title}`
    })
    .catch(() => {
      router.push({ name: 'not-found' })
    })
}

const deleteAttachedFile = () => {
  $toast.confirm('Anhang wirklich löschen?', () => {
    AttachmentService.deleteAttachedFile(attachment.value).then(data => {
      router.push({ name: 'dashboard' })
    })
  })
}

loadFromServer()
</script>

<template>
  <div>
    <model-header
      colorClass="bg-green"
      secondaryColorClass="bg-green/50"
      v-if="attachment"
    >
      <template v-slot:description>Anhang</template>
      <template v-slot:content>
        <h2 class="text-4xl text-center">{{ attachment.title }}</h2>
      </template>
    </model-header>
    <section v-if="attachment" class="grid grid-cols-6 my-8 width-wrapper">
      <div class="col-span-4 px-8 py-8">
        <template v-if="attachment.type == 'AttachedFile'">
          <img
            v-if="attachment.is_image"
            :src="attachment.serve_url"
            class="w-full h-auto"
          />
          <template v-else-if="attachment.mime_type == 'application/pdf'">
            <iframe :src="attachment.serve_url" class="w-full h-96"></iframe>
          </template>
          <template v-else-if="attachment.mime_type == 'video/mp4'">
            <video controls class="w-full aspect-video">
              <source :src="attachment.serve_url" type="video/mp4" />
              <div
                class="px-8 py-12 bg-gray-100 border-[1px] flex flex-col items-center justify-center gap-4 text-center border-gray-400 border-dashed rounded-md"
              >
                <icon name="file" class="text-gray-400 size-20"></icon>
                <p>Keine Vorschau für diesen Dateityp</p>
              </div>
            </video>
          </template>
          <template v-else-if="attachment.mime_type == 'audio/mpeg'">
            <audio controls class="w-full">
              <source :src="attachment.serve_url" type="audio/mpeg" />
              <div
                class="px-8 py-12 bg-gray-100 border-[1px] flex flex-col items-center justify-center gap-4 text-center border-gray-400 border-dashed rounded-md"
              >
                <icon name="file" class="text-gray-400 size-20"></icon>
                <p>Keine Vorschau für diesen Dateityp</p>
              </div>
            </audio>
          </template>
          <div
            v-else
            class="px-8 py-12 bg-gray-100 border-[1px] flex flex-col items-center justify-center gap-4 text-center border-gray-400 border-dashed rounded-md"
          >
            <icon name="file" class="text-gray-400 size-20"></icon>
            <p>Keine Vorschau für diesen Dateityp</p>
          </div>
        </template>
        <template v-else-if="attachment.type == 'AttachedUrl'">
          <div
            class="p-4 bg-gray-100 border-[1px] text-center border-gray-400 border-dashed rounded-md"
          >
            <a
              :href="attachment.serve_url"
              target="_blank"
              class="font-semibold underline text-blue"
              >{{ attachment.serve_url }}</a
            >
          </div>
        </template>
        <h4 class="mt-6">Beschreibung:</h4>
        <p>{{ attachment.description }}</p>
      </div>
      <div class="self-start col-span-2 px-8 py-8 border-l sticky-sidebar">
        <div class="flex flex-col gap-8">
          <div v-if="attachment.approved">
            <icon name="approved" class="mr-2 text-green size-6"></icon>
            geprüfter Anhang
          </div>
          <div>
            <h4 class="mb-2 text-sm text-gray-300">Ersteller*in</h4>
            <p>{{ attachment.created_by.name }}</p>
          </div>
          <div>
            <h4 class="mb-2 text-sm text-gray-300">erstellt am</h4>
            <p>{{ $filters.formatedDate(attachment.created_at) }}</p>
          </div>
          <div v-if="attachment.license">
            <h4 class="mb-2 text-sm text-gray-300">Lizenz</h4>
            <p>{{ attachment.license.title }}</p>
          </div>
          <div v-if="attachment.source">
            <h4 class="mb-2 text-sm text-gray-300">Quelle</h4>
            <p>{{ attachment.source }}</p>
          </div>
          <div
            class="grid grid-cols-2"
            v-if="attachment.type == 'AttachedFile'"
          >
            <div>
              <h4 class="mb-2 text-sm text-gray-300">Typ</h4>
              <p>{{ $filters.fileNameToFileType(attachment.filename) }}</p>
            </div>
            <div>
              <h4 class="mb-2 text-sm text-gray-300">Größe</h4>
              <p>
                {{ $filters.bytesToHumandReadableSize(attachment.filesize) }}
              </p>
            </div>
          </div>
          <a
            target="blank"
            :href="attachment.serve_url"
            class="text-center default-button"
          >
            <template v-if="attachment.type == 'AttachedUrl'">Öffnen</template>
            <template v-else>Download</template></a
          >
          <div
            class="cursor-pointer"
            v-if="
              attachment.type == 'AttachedFile' &&
              hasPermission('delete attached files')
            "
            @click="deleteAttachedFile"
          >
            <icon name="trash" class="text-black" />
            <span class="inline-block ml-2 underline">Löschen</span>
          </div>
        </div>
      </div>
    </section>
    <section class="my-28 width-wrapper" v-if="attachment">
      <separator>
        <template v-if="attachment.articles.length == 1">
          Enthalten in folgendem Beitrag
        </template>
        <template v-else>Enthalten in folgenden Beiträgen</template>
      </separator>
      <div
        class="grid grid-cols-4 gap-4"
        v-if="attachment.articles && attachment.articles.length"
      >
        <article-card
          v-for="article in attachment.articles"
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
