<script setup>
import { ref } from 'vue'
import ArticleService from '@/services/ArticleService'

const showClapCount = ref(false)

const props = defineProps({
  article: Object,
})

const clapArticle = () => {
  ArticleService.clapArticle(props.article.slug).then(data => {
    showClapCount.value = true
    props.article.claps = data.claps
    setTimeout(() => (showClapCount.value = false), 2000)
  })
}
</script>
<template>
  <div class="relative inline-block mt-8">
    <div class="absolute -translate-x-1/2 left-1/2 -top-[50%]">
      <transition name="slide">
        <p class="text-sm text-gray-300" v-if="showClapCount">
          {{ article.claps }}
        </p>
      </transition>
    </div>
    <button
      @click="clapArticle"
      class="relative inline-flex items-center p-3 transition-all ease-in-out border-2 border-gray-300 rounded-full clap-button hover:border-blue group hover:scale-95 hover:shadow-md"
    >
      <icon
        name="clap"
        class="text-gray-300 group-hover:text-blue size-6"
      ></icon>
    </button>
  </div>
</template>
<style scoped>
.slide-leave-active,
.slide-enter-active {
  transition: 0.5s;
  opacity: 1;
}
.slide-enter-from {
  transform: translate(0, 100%);
  opacity: 0;
}
.slide-leave-to {
  transform: translate(0, -100%);
  opacity: 0;
}

.clap-button::after {
  content: '';
  display: inline-block;
  height: 100%;
  width: 100%;
  border-radius: 100px;
  position: absolute;
  top: 0;
  left: 0;
  z-index: -1;
  transition: ease-out 1s;
}

.clap-button:focus::after {
  animation: pulseOut 0.6s normal forwards ease-out;
}
.clap-button:focus svg {
  animation: turn 0.5s normal forwards ease-out;
}
.clap-button:active::after {
  animation: none;
}
.clap-button:active svg {
  animation: none;
}
@keyframes pulseOut {
  from {
    @apply bg-blue;
    opacity: 1;
    transform: scaleX(0) scaleY(0);
  }
  to {
    transform: scaleX(10) scaleY(10);
    opacity: 0;
  }
}
@keyframes turn {
  from {
    transform: rotate(0deg);
  }
  to {
    transform: rotate(360deg);
  }
}
</style>
