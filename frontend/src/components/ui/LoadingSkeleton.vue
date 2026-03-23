<template>
  <div :class="[className, 'bg-gray-100 animate-pulse overflow-hidden relative']">
    <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/40 to-transparent translate-x-[-100%] animate-[shimmer_2s_infinite]"></div>
  </div>
</template>

<script setup>
defineProps({
  className: String
})
</script>

<style>
@keyframes shimmer {
  100% { transform: translateX(100%); }
}
</style>
