<script setup>
const props = defineProps({
  model: Object,
  showType: {
    type: Boolean,
    default: true,
  },
  navigate: {
    type: Boolean,
    default: true,
  },
  dragable: {
    type: Boolean,
    default: false,
  },
})
</script>

<template>
  <p>
    <span class="mr-1" v-if="showType">
      <template v-if="model.type == 'Article'">Beitrag</template>
      <template v-else-if="model.type == 'Collection'">Sammlung</template>
      <template v-else>Anhang</template>
    </span>

    <!-- articles -->
    <span
      v-if="model.type == 'Article'"
      class="font-semibold cursor-pointer text-orange"
    >
      <icon
        name="drag"
        class="inline-block mr-1 cursor-grab"
        v-if="dragable"
      ></icon>

      <router-link v-if="navigate" :to="model.url">
        {{ model.title }}
      </router-link>
      <template v-else>{{ model.title }}</template>
    </span>

    <!-- collections -->
    <span
      class="font-semibold text-blue-400 cursor-pointer"
      v-else-if="model.type == 'Collection'"
    >
      <icon
        name="drag"
        class="inline-block mr-1 text-gray-200 cursor-grab"
        v-if="dragable"
      ></icon>
      <router-link v-if="navigate" :to="model.url">
        {{ model.title }}
      </router-link>
      <template v-else>{{ model.title }}</template>
    </span>

    <!-- images -->
    <a
      v-else-if="model.type == 'Image'"
      :href="model.url"
      target="_blank"
      class="font-semibold cursor-pointer text-green"
    >
      {{ model.title ?? '[Ohne Titel]' }}
    </a>

    <!-- attachments -->
    <span v-else>
      <a
        v-if="navigate"
        :href="model.url"
        target="_blank"
        class="font-semibold cursor-pointer text-green"
      >
        {{ model.title ?? '[Ohne Titel]' }}
      </a>
      <span class="font-semibold cursor-pointer text-green" v-else>{{
        model.title ?? '[Ohne Titel]'
      }}</span>

      <span class="inline-block ml-2 text-gray-200"
        >erstellt {{ $filters.formatedDate(model.created_at) }}</span
      >
    </span>
  </p>
</template>

<style scoped></style>
