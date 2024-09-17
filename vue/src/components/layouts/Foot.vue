<script setup>
import { ref } from 'vue'

const scrollTop = () => {
  window.scrollTo({ top: 0, behavior: 'smooth' })
}

const links = ref([
  {
    title: 'Impressum & Datenschutz',
    link: 'https://www.offene-werkstaetten.org/de/seite/impressum-datenschutz',
  },
  {
    title: 'Spenden',
    link: 'https://www.offene-werkstaetten.org/de/seite/unsere-ziele#spenden',
  },
  {
    title: 'API',
    link: 'https://cowiki.de/docs',
  },
  {
    title: 'Github',
    link: 'https://github.com/Verbund-Offener-Werkstaetten/vow-cowiki',
  },
])
</script>

<template>
  <footer>
    <a
      v-if="false"
      href="#"
      @click.prevent="scrollTop()"
      class="button-arrow-up"
      title="Nach oben"
    >
      <svg
        xmlns="http://www.w3.org/2000/svg"
        width="30"
        height="30"
        viewBox="0 0 24 24"
      >
        <path d="M7.41 15.41L12 10.83l4.59 4.58L18 14l-6-6-6 6z" />
      </svg>
    </a>
    <div class="bg-blue-200 before-clip"></div>
    <div class="text-white bg-blue content-clip">
      <div class="grid grid-cols-3 pt-12 pb-16 gap-y-8 gap-x-24 width-wrapper">
        <h1 class="col-span-3 text-2xl text-white font-rubik">
          <router-link to="/">
            <span>VOW_</span>
            <span class="font-bold">COWIKI</span>
          </router-link>
        </h1>
        <div class="col-span-3 md:col-span-1">
          <p>
            <a
              class="block mb-2 uppercase"
              v-for="menuItem in links"
              target="_blank"
              :href="menuItem.link"
              >{{ menuItem.title }}</a
            >
          </p>
        </div>
        <div class="col-span-3 md:col-span-1">
          <p>Ein Projekt der<br /><br /></p>
          <a
            class="max-w-[250px] block"
            href="https://anstiftung.de/"
            target="_blank"
          >
            <img src="/img/anstiftung_logo.svg" alt="Logo der anstiftung" />
          </a>
        </div>
        <div class="col-span-3 md:col-span-1">
          <p>In Kooperation mit<br /><br /></p>
          <a
            class="max-w-[250px] block"
            href="http://www.offene-werkstaetten.org"
            target="_blank"
          >
            <img
              class="mt-4"
              src="/img/vow_logo.svg"
              alt="Logo des Verbund offener Werkstätten"
            />
          </a>
        </div>
      </div>
    </div>
  </footer>
</template>

<style scoped>
.button-arrow-up {
  @apply flex items-center justify-center rounded-full w-12 h-12;
  @apply absolute left-1/2 -bottom-6 -translate-x-1/2;
  @apply fill-blue-400 bg-white hover:bg-gray-200 transition-colors;
}
.content-clip {
  clip-path: polygon(0 20px, 0 100%, 100% 100%, 100% 0);
  padding-top: 20px;
}

.before-clip {
  margin-bottom: -20px;
  height: 100px;
  clip-path: polygon(0 0, 0px 100%, 100% 100%, 100% 0px, 70% 50%);
}
</style>
