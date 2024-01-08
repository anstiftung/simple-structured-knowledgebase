<script setup>
import { storeToRefs } from 'pinia'
import { useModalStore } from '@/stores/modal'
import { useUserStore } from '@/stores/user'
import AddAttachment from '@/components/attachments/AddAttachment.vue'

const modal = useModalStore()
// If you need UserPermissions, you'll need the next three lines
const store = useUserStore()
const { hasPermission } = storeToRefs(store)
store.initUser()

const showCreateAttachmentModal = () => {
  modal.open(AddAttachment, [
    {
      label: 'Save',
      callback: dataFromView => {
        console.log(dataFromView)
        modal.close()
      },
    },
  ])
}
</script>
<template>
  <section class="bg-white">
    <div class="flex items-baseline justify-between py-12 width-wrapper">
      <h2 class="text-2xl font-bold">Dashboard</h2>
      <div v-if="store.id" class="flex gap-4">
        <button
          class="default-button"
          @click.prevent="showCreateAttachmentModal"
        >
          Anhang erstellen
        </button>
        <button class="default-button">Beitrag erstellen</button>
        <button v-if="hasPermission('add collections')" class="default-button">
          Sammlung erstellen
        </button>
      </div>
      <div v-else>
        <p>
          <strong>Fehler:</strong> Du bist nicht authorisiert diese Inhalte zu
          sehen.
        </p>
      </div>
    </div>
  </section>
</template>
