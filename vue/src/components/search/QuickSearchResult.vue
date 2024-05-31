<script setup>
import ItemLine from '@/components/atoms/ItemLine.vue'
import resultArticles from '@/components/search/quick/resultArticles.vue'
import resultCollections from '@/components/search/quick/resultCollections.vue'
import resultAttachments from '@/components/search/quick/resultAttachments.vue'
import resultImages from '@/components/search/quick/resultImages.vue'

defineEmits(['resultSelected'])

const props = defineProps({
  results: Object,
  modelType: String,
})
</script>

<template>
  <div class="quicksearch-result">
    <template v-if="modelType == 'articles'">
      <resultArticles
        :articles="results"
        @result-selected="article => $emit('resultSelected', article)"
      ></resultArticles>
    </template>

    <template v-else-if="modelType == 'collections'">
      <resultCollections
        :collections="results"
        @result-selected="collection => $emit('resultSelected', collection)"
      ></resultCollections>
    </template>

    <template v-else-if="modelType == 'attachments'">
      <resultAttachments
        :attachments="results"
        @result-selected="attachment => $emit('resultSelected', attachment)"
      ></resultAttachments>
    </template>

    <template v-else-if="modelType == 'images'">
      <resultImages
        :images="results"
        @result-selected="image => $emit('resultSelected', image)"
      ></resultImages>
    </template>

    <item-line
      v-for="item in results"
      v-else
      :model="item"
      :showType="false"
      :navigate="false"
      @click.prevent="$emit('resultSelected', item)"
    />
  </div>
</template>

<style scoped></style>
