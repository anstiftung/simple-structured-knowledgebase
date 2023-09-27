<script setup></script>

<template>
  <section class="bg-gray-100">
    <div class="relative width-wrapper">
      <img src="/img/bg.jpg" />
      <div
        class="absolute flex w-full gap-2 px-4 py-2 -translate-x-1/2 bg-white rounded-md md:w-6/12 top-2/3 left-1/2 drop-shadow-lg"
      >
        <input
          class="w-full outline-none placeholder:text-gray-200"
          type="text"
          placeholder="Suche…"
        />
        <svg
          role="button"
          xmlns="http://www.w3.org/2000/svg"
          width="24"
          class="fill-gray-800"
          height="24"
          viewBox="0 0 24 24"
        >
          <path
            d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"
          />
        </svg>
      </div>
    </div>
  </section>
  <section class="bg-white">
    <!-- todo: generalize -->
    <div class="py-12 width-wrapper">
      <h2 class="mb-4 text-2xl font-bold">Neueste Elemente</h2>
      <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
        <div class="rounded-md aspect-square bg-blue"></div>
        <div class="rounded-md aspect-square bg-blue"></div>
        <div class="rounded-md aspect-square bg-blue"></div>
        <div class="rounded-md aspect-square bg-blue"></div>
        <div class="rounded-md aspect-square bg-blue"></div>
        <div class="rounded-md aspect-square bg-blue"></div>
        <div class="rounded-md aspect-square bg-blue"></div>
      </div>
    </div>
  </section>
</template>
