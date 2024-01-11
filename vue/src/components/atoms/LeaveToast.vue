<script setup></script>

<template>
  <div>
    <p>Ungespeicherte Änderungen! Diese Seite wirklich verlassen?</p>
    <div class="flex justify-end gap-2 mt-4">
      <button
        class="secondary-button secondary-button-white"
        @click="$emit('close-toast')"
      >
        Abbrechen
      </button>
      <button class="default-button" @click="$emit('allow-route-change')">
        OK
      </button>
    </div>
  </div>
</template>

<style scoped></style>
