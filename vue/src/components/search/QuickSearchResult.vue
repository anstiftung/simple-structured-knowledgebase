<script setup>
import ItemLine from '@/components/atoms/ItemLine.vue'
import ResultArticles from '@/components/search/quick/ResultArticles.vue'
import ResultCollections from '@/components/search/quick/ResultCollections.vue'
import ResultAttachments from '@/components/search/quick/ResultAttachments.vue'
import ResultImages from '@/components/search/quick/ResultImages.vue'

defineEmits(['resultSelected'])

const props = defineProps({
  results: Object,
  modelType: String,
})
</script>

<template>
  <div class="quicksearch-result">
    <template v-if="modelType == 'articles'">
      <result-articles
        :articles="results"
        @result-selected="article => $emit('resultSelected', article)"
      ></result-articles>
    </template>

    <template v-else-if="modelType == 'collections'">
      <resultCollections
        :collections="results"
        @result-selected="collection => $emit('resultSelected', collection)"
      ></resultCollections>
    </template>

    <template v-else-if="modelType == 'attachments'">
      <result-attachments
        :attachments="results"
        @result-selected="attachment => $emit('resultSelected', attachment)"
      ></result-attachments>
    </template>

    <template v-else-if="modelType == 'images'">
      <result-images
        :images="results"
        @result-selected="image => $emit('resultSelected', image)"
      ></result-images>
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
