<script setup>
const scrollTop = () => {
  window.scrollTo({ top: 0, behavior: 'smooth' })
}
</script>

<template>
  <footer>
    <div class="relative pt-8 pb-12 bg-gray-100">
      <div class="grid items-baseline grid-cols-2 gap-4 text-blue">
        <span class="text-right">Cowiki ist eine kollaborative</span>
        <span class="text-xl font-bold">Offene</span>
        <span class="text-right">Informationsseite zur Sammlung</span>
        <span class="text-xl font-bold">Werkstätten</span>
        <span class="text-right">von Praxiswissen zum Thema</span>
        <span class="text-xl font-bold">selbermachen</span>
      </div>
      <a
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
    </div>
    <div class="text-white bg-blue">
      <div
        class="flex flex-wrap items-end justify-between gap-4 py-8 width-wrapper"
      >
        <p>
          VOW_COWIKI<br />
          VERBUND OFFENER WERKSTÄTTEN_<br />
          <a href="http://www.offene-werkstaetten.org"
            >www.offene-werkstaetten.org</a
          >
        </p>
        <p class="md:text-right">
          <a
            target="_blank"
            href="https://github.com/Verbund-Offener-Werkstaetten/vow-cowiki"
            >Github</a
          >
          <br />
          <a
            target="_blank"
            href="https://www.offene-werkstaetten.org/de/seite/impressum-datenschutz"
            >Impressum & Datenschutz</a
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
  @apply fill-blue bg-white hover:bg-gray-200 transition-colors;
}
</style>
