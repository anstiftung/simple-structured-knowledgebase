<script setup>
import Separator from '@/components/layouts/Separator.vue'
</script>
<template>
  <div id="how-to-cowiki" class="mb-12">
    <separator>
      <h3>Wie funktioniert das COWIKI?</h3>
    </separator>

    <div class="grid sm:grid-cols-2 pt-2 pb-8 gap-x-6 gap-y-6 w-9/12 mx-auto">
      <router-link
        to="/beitrag/was-ist-ein-cowiki-beitrag"
        class="aspect-square border border-gray flex justify-center items-center transition-all hover:shadow-md"
      >
        <div class="text-center">
          <img
            class="max-w-[150px] inline-block mb-4"
            src="/img/explain01.svg"
          />
          <p class="max-w-[200px]">
            Das <span class="font-bold text-blue-600">COWIKI</span> besteht aus
            vielen <span class="font-bold text-orange">Beiträgen</span>.
          </p>
        </div>
      </router-link>

      <router-link
        to="/beitrag/geprufte-beitrage-beklatschen-und-kommentieren"
        class="aspect-square border border-gray flex justify-center items-center transition-all hover:shadow-md"
      >
        <div class="text-center">
          <img
            class="max-w-[150px] inline-block mb-4"
            src="/img/explain02.svg"
          />
          <p class="max-w-[200px]">
            Geprüfte <span class="font-bold text-orange">Beiträge</span>?
            <span class="font-bold text-blue-600">Beklatschen</span> und
            <span class="font-bold text-blue-600">kommentieren</span>?
          </p>
        </div>
      </router-link>

      <router-link
        to="/beitrag/was-ist-ein-cowiki-anhang"
        class="aspect-square border border-gray flex justify-center items-center transition-all hover:shadow-md"
      >
        <div class="text-center">
          <img
            class="max-w-[150px] inline-block mb-4"
            src="/img/explain03.svg"
          />
          <p class="max-w-[200px]">
            Gute <span class="font-bold text-green">Anhänge</span> kommen immer
            wieder in Beiträgen vor.
          </p>
        </div>
      </router-link>

      <router-link
        to="/beitrag/was-ist-eine-cowiki-sammlung"
        class="aspect-square border border-gray flex justify-center items-center transition-all hover:shadow-md"
      >
        <div class="text-center">
          <img
            class="max-w-[150px] inline-block mb-4"
            src="/img/explain04.svg"
          />
          <p class="max-w-[200px]">
            Zur Orientierug bündeln wir Beiträge in thematischen
            <span class="font-bold text-blue-400">Sammlungen</span>.
          </p>
        </div>
      </router-link>
    </div>
    <div class="flex justify-center">
      <router-link
        to="/beitrag/cowiki-introduction"
        class="default-button !px-12"
      >
        zur Anleitung
      </router-link>
    </div>
  </div>
</template>
