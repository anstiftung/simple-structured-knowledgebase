<script setup>
import ItemLine from '@/components/atoms/ItemLine.vue'
import HeaderArticles from '@/components/search/quick/headerArticles.vue'
import resultArticles from '@/components/search/quick/resultArticles.vue'
import HeaderCollections from '@/components/search/quick/headerCollections.vue'
import resultCollections from '@/components/search/quick/resultCollections.vue'
import HeaderAttachments from '@/components/search/quick/headerAttachments.vue'
import resultAttachments from '@/components/search/quick/resultAttachments.vue'
import HeaderImages from '@/components/search/quick/headerImages.vue'
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
      <table class="w-full mb-4" v-if="results.length > 0">
        <headerArticles />
        <resultArticles
          :articles="results"
          @result-selected="article => $emit('resultSelected', article)"
        ></resultArticles>
      </table>
    </template>

    <template v-else-if="modelType == 'collections'">
      <table class="w-full mb-4" v-if="results.length > 0">
        <headerCollections />
        <resultCollections
          :collections="results"
          @result-selected="collection => $emit('resultSelected', collection)"
        ></resultCollections>
      </table>
    </template>

    <template v-else-if="modelType == 'attachments'">
      <table class="w-full mb-4" v-if="results.length > 0">
        <headerAttachments />
        <resultAttachments
          :attachments="results"
          @result-selected="attachment => $emit('resultSelected', attachment)"
        ></resultAttachments>
      </table>
    </template>

    <template v-else-if="modelType == 'images'">
      <table class="w-full mb-4" v-if="results.length > 0">
        <headerImages />
        <resultImages
          :images="results"
          @result-selected="image => $emit('resultSelected', image)"
        ></resultImages>
      </table>
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
