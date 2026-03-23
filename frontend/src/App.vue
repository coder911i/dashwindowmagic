<template>
  <div id="app" class="font-sans antialiased bg-gray-50 min-h-screen text-gray-900 overflow-x-hidden">
    <!-- Global Loading Overlay -->
    <div v-if="isLoading" class="fixed inset-0 z-[9999] bg-white/80 backdrop-blur-md flex items-center justify-center">
      <div class="flex flex-col items-center gap-4">
        <div class="w-12 h-12 border-4 border-blue-100 border-t-blue-600 rounded-full animate-spin"></div>
        <p class="text-[10px] font-black uppercase tracking-widest text-gray-400">Loading Watering CRM...</p>
      </div>
    </div>

    <!-- Main Router View -->
    <router-view v-slot="{ Component }">
      <transition 
        name="page" 
        mode="out-in"
      >
        <component :is="Component" />
      </transition>
    </router-view>

    <!-- Global Toast / Notification System (Placeholder) -->
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'

const isLoading = ref(true)

onMounted(() => {
  // Simulate initial app boot
  setTimeout(() => {
    isLoading.value = false
  }, 1000)
})
</script>

<style>
/* Global styles moved to style.css, but App specific ones go here */
#app {
  -webkit-tap-highlight-color: transparent;
}

/* Page Transition: Slide + Fade */
.page-enter-active,
.page-leave-active {
  transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
}

.page-enter-from {
  opacity: 0;
  transform: translateY(10px);
}

.page-leave-to {
  opacity: 0;
  transform: translateY(-10px);
}
</style>
