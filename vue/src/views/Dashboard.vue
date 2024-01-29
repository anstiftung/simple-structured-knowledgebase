<script setup>
import { ref, computed, onBeforeMount } from 'vue'
import { storeToRefs } from 'pinia'
import { useRouter, useRoute } from 'vue-router'

import { useModalStore } from '@/stores/modal'
import { useUserStore } from '@/stores/user'

import draggable from 'vuedraggable'

import ArticleService from '@/services/ArticleService'
import CollectionService from '@/services/CollectionService'
import AttachmentService from '@/services/AttachmentService'

import AddAttachments from '@/components/attachments/AddAttachments.vue'
import EditAttachments from '@/components/attachments/EditAttachments.vue'

import SearchForm from '@/components/SearchForm.vue'
import ItemLine from '@/components/atoms/ItemLine.vue'
import CollectionLine from '@/components/atoms/CollectionLine.vue'

const router = useRouter()
const route = useRoute()

const recentCollections = ref([])
const recentArticles = ref([])
const recentAttachedUrls = ref([])
const recentAttachedFiles = ref([])

const invalidAttachedFiles = ref({ data: [], meta: null })
const invalidAttachedUrls = ref({ data: [], meta: null })

const featuredCollections = ref([])

const initialQuery = ref('')

const modal = useModalStore()
// If you need UserPermissions, you'll need the next three lines
const userStore = useUserStore()
const { hasPermission } = storeToRefs(userStore)

const searchQueryUpdated = searchQuery => {
  router.replace({ query: { q: searchQuery } })
}

onBeforeMount(() => {
  // if present load url query
  if (route.query.q) {
    initialQuery.value = route.query.q
  }
})

const showCreateAttachmentModal = () => {
  modal.open(AddAttachments, {}, savedAttachments => {
    if (savedAttachments && savedAttachments.length) {
      const props = { attachments: savedAttachments }
      modal.open(EditAttachments, props, () => {
        loadFromServer()
      })
    }
  })
}

userStore.initUser().then(() => {
  loadFromServer()
})

const editAttachment = attachment => {
  const props = { attachments: [attachment] }
  modal.open(EditAttachments, props, () => {
    loadFromServer()
  })
}

const sortCallback = event => {
  // make order-property consistent with sorting
  let i = 0
  featuredCollections.value.map(element => {
    element.order = i
    i++
  })

  CollectionService.reorderFeaturedCollections(featuredCollections.value).then(
    data => (featuredCollections.value = data),
  )
}

const loadFromServer = () => {
  ArticleService.getArticles(1, userStore.id).then(({ data, meta }) => {
    recentArticles.value = data.slice(0, Math.min(5, data.length))
  })

  AttachmentService.getAttachmentUrls(1, userStore.id).then(
    ({ data, meta }) => {
      recentAttachedUrls.value = data.slice(0, Math.min(5, data.length))
    },
  )

  AttachmentService.getAttachmentFiles(1, userStore.id).then(
    ({ data, meta }) => {
      recentAttachedFiles.value = data.slice(0, Math.min(5, data.length))
    },
  )

  AttachmentService.getAttachmentFiles(1, userStore.id, true).then(
    ({ data, meta }) => {
      invalidAttachedFiles.value.data = data
      invalidAttachedFiles.value.meta = meta
    },
  )

  AttachmentService.getAttachmentUrls(1, userStore.id, true).then(
    ({ data, meta }) => {
      invalidAttachedUrls.value.data = data
      invalidAttachedUrls.value.meta = meta
    },
  )

  CollectionService.getCollections(1, {
    featured: true,
    creatorId: userStore.id,
  }).then(({ data, meta }) => {
    recentCollections.value = data
  })

  CollectionService.getCollections(1, { featured: true }).then(
    ({ data, meta }) => {
      featuredCollections.value = data
    },
  )
}

const activities = computed(() => {
  let activities = recentArticles.value
    .concat(recentAttachedFiles.value)
    .concat(recentAttachedUrls.value)
    .concat(recentCollections.value)
  activities = activities.sort((a, b) => a.created_at < b.created_at)
  return activities
})

const invalidAttachments = computed(() => {
  let attachments = invalidAttachedFiles.value.data.concat(
    invalidAttachedUrls.value.data,
  )
  attachments = attachments.sort((a, b) => a.created_at < b.created_at)
  return attachments
})

const invalidAttachmentsLimited = computed(() => {
  return invalidAttachments.value.slice(
    0,
    Math.min(10, invalidAttachments.value.length),
  )
})
const invalidAttachmentsTotal = computed(() => {
  if (invalidAttachedFiles.value.meta && invalidAttachedUrls.value.meta) {
    return (
      invalidAttachedFiles.value.meta.total +
      invalidAttachedUrls.value.meta.total
    )
  } else {
    return 0
  }
})
</script>
<template>
  <section class="bg-white">
    <div
      class="flex items-baseline justify-between py-12 gap-x-12 width-wrapper"
    >
      <search-form
        placeholder="Suche in meinen Beiträgen, Anhängen und Sammlungen"
        class="grow"
        @queryChanged="searchQueryUpdated"
        :initialQuery="initialQuery"
      />
      <div v-if="userStore.id" class="flex gap-4">
        <button
          class="default-button"
          @click.prevent="showCreateAttachmentModal"
        >
          Anhang erstellen
        </button>
        <router-link :to="{ name: 'articleCreate' }" class="default-button"
          >Beitrag erstellen</router-link
        >
        <router-link
          v-if="hasPermission('add collections')"
          :to="{ name: 'collectionCreate' }"
          class="default-button"
          >Sammlung erstellen</router-link
        >
      </div>
    </div>
    <div class="grid grid-cols-2 divide-x width-wrapper">
      <div class="">
        <div class="pt-3 pb-2 pl-2 font-semibold border-y">
          <h3 class="text-black">Letzte Aktivitäten</h3>
        </div>
        <div class="py-4 pl-2" v-if="activities">
          <item-line
            :model="activity"
            class="mb-2"
            v-for="activity in activities"
          />
        </div>
      </div>
      <div class="">
        <div class="pt-3 pb-2 pl-2 border-y">
          <h3 class="font-semibold text-black">
            Anhänge mit unzureichenden Metadaten
          </h3>
          <p class="text-gray-200">
            Solche Anhänge können nicht genutzt und veröffentlicht werden.
          </p>
        </div>
        <div class="min-h-[200px] py-4 pl-2">
          <p class="mb-2" v-for="attachment in invalidAttachmentsLimited">
            <span
              >{{ attachment.type == 'AttachedUrl' ? 'URL ' : 'Datei ' }}
            </span>
            <span
              class="font-semibold cursor-pointer text-green"
              @click="editAttachment(attachment)"
            >
              {{
                attachment.type == 'AttachedUrl'
                  ? attachment.url
                  : attachment.filename
              }}
            </span>
          </p>
          <p
            class="text-gray-200"
            v-if="invalidAttachmentsTotal > invalidAttachmentsLimited.length"
          >
            und
            {{ invalidAttachmentsTotal - invalidAttachmentsLimited.length }}
            {{
              invalidAttachmentsTotal - invalidAttachmentsLimited.length > 1
                ? 'weitere'
                : 'weiterer'
            }}
          </p>
          <p class="text-gray-200" v-if="!invalidAttachmentsLimited.length">
            Keine Anhänge vorhanden!
          </p>
        </div>
        <div class="pt-3 pb-2 pl-4 border-y">
          <h3 class="font-semibold text-black">
            Sammlungen auf der Startseite
          </h3>
        </div>
        <div class="pl-4 mt-5 mb-5">
          <button
            v-if="hasPermission('feature collections')"
            class="secondary-button dense"
          >
            Sammlung zur Startseite hinzufügen
          </button>
        </div>
        <div class="min-h-[200px]">
          <div class="pt-3 pl-4" v-if="featuredCollections">
            <draggable
              v-model="featuredCollections"
              group="people"
              @change="sortCallback"
              item-key="id"
              :disabled="!hasPermission('feature collections')"
            >
              <template #item="{ element }">
                <collection-line
                  :collection="element"
                  class="mb-2"
                  :dragable="hasPermission('feature collections')"
                />
              </template>
            </draggable>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>
