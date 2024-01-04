<script setup>
import { ref } from 'vue'

const scrollTop = () => {
  window.scrollTo({ top: 0, behavior: 'smooth' })
}

const links = ref([
  {
    title: 'Sitemap',
    link: '/sitemap',
  },
  {
    title: 'Impressum & Datenschutz',
    link: 'https://www.offene-werkstaetten.org/de/seite/impressum-datenschutz',
  },
  {
    title: 'Mein Cowiki',
    link: '/dashboard',
  },
  {
    title: 'Einstellungen',
    link: '/dashboard',
  },
  {
    title: 'Spenden',
    link: 'wohin.de',
  },
])
</script>

<template>
  <footer class="font-rubik">
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
      <div
        class="flex flex-wrap items-end justify-between gap-12 pt-12 pb-16 width-wrapper"
      >
        <div>
          <h1 class="text-2xl text-white">
            <router-link to="/">
              <span>VOW_</span>
              <span class="font-bold">COWIKI</span>
            </router-link>
          </h1>
          <p class="mt-6">
            Eine Initiative von:<br />
            <b>VERBUND OFFENER WERKSTÄTTEN</b><br />
            <a href="http://www.offene-werkstaetten.org"
              >www.offene-werkstaetten.org</a
            >
          </p>
          <p class="mt-4">
            Gefördert durch:<br />Stiftung für irgendwas Deutschland e.V.
          </p>
        </div>
        <p class="md:text-right">
          <a
            class="block mb-2 uppercase"
            v-for="menuItem in links"
            target="_blank"
            :href="menuItem.link"
            >{{ menuItem.title }}</a
          >
        </p>
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
