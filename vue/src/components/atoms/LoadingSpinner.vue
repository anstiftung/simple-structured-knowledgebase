<template>
  <!-- from: cssloaders.github.io -->
  <div class="block w-full text-center">
    <span class="loader"></span>
  </div>
</template>
<style scoped>
.loader {
  width: 20px;
  height: 20px;
  @apply border-[3px] border-blue;
  border-radius: 50%;
  display: inline-block;
  box-sizing: border-box;
  position: relative;
  animation: pulse 1s linear infinite;
}
.loader:after {
  content: '';
  position: absolute;
  width: 20px;
  height: 20px;
  @apply border-[3px] border-blue;
  border-radius: 50%;
  display: inline-block;
  box-sizing: border-box;
  left: 50%;
  top: 50%;
  transform: translate(-50%, -50%);
  animation: scaleUp 1s linear infinite;
}

@keyframes scaleUp {
  0% {
    transform: translate(-50%, -50%) scale(0);
  }
  60%,
  100% {
    transform: translate(-50%, -50%) scale(1);
  }
}
@keyframes pulse {
  0%,
  60%,
  100% {
    transform: scale(1);
  }
  80% {
    transform: scale(1.2);
  }
}
</style>
