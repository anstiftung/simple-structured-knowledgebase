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
import ModelSelector from '@/components/atoms/ModelSelector.vue'

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

const markCollectionFeatured = () => {
  modal.open(ModelSelector, { modelType: 'collections' }, collection => {
    if (collection && !collection.featured) {
      collection.featured = true
      CollectionService.updateCollection(collection).then(() => {
        loadFromServer()
      })
    }
  })
}

const markCollectionUnFeatured = collection => {
  collection.featured = false
  CollectionService.updateCollection(collection).then(() => {
    loadFromServer()
  })
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
    creatorId: userStore.id,
  }).then(({ data, meta }) => {
    recentCollections.value = data.slice(0, Math.min(5, data.length))
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

loadFromServer()
</script>
<template>
  <section class="bg-white">
    <div
      class="flex items-baseline justify-between py-12 gap-x-12 width-wrapper"
    >
      <search-form
        placeholder="Suche in Beiträgen, Anhängen und Sammlungen"
        class="grow"
        @queryChanged="searchQueryUpdated"
        :initialQuery="initialQuery"
        :onlyPublished="false"
      />
      <div v-if="userStore.id" class="flex gap-4">
        <button
          class="secondary-button"
          @click.prevent="showCreateAttachmentModal"
        >
          Anhänge
        </button>
        <router-link :to="{ name: 'articleCreate' }" class="secondary-button"
          >Beiträge</router-link
        >
        <router-link
          v-if="hasPermission('add collections')"
          :to="{ name: 'collectionCreate' }"
          class="secondary-button"
          >Sammlungen</router-link
        >
      </div>
    </div>
    <div class="width-wrapper">
      <div class="pt-3 pb-2 pl-2 font-semibold border-y">
        <h3 class="text-black">Suchergebnisse</h3>
      </div>
      <div class="py-4 pl-2" v-if="activities">
        <item-line
          :model="activity"
          class="mb-2"
          v-for="activity in activities"
        />
      </div>
    </div>
  </section>
</template>
