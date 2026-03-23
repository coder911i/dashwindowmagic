<template>
  <div class="min-h-[80vh] flex items-center justify-center p-8 bg-white border border-gray-100 rounded-3xl">
    <div class="max-w-md w-full text-center">
      <div class="mb-8 flex justify-center">
        <div class="w-16 h-16 bg-blue-600 rounded-2xl flex items-center justify-center shadow-lg shadow-blue-500/20">
          <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
          </svg>
        </div>
      </div>
      <h1 class="text-3xl font-black text-gray-900 uppercase tracking-tighter mb-2">Coming Soon</h1>
      <p class="text-gray-500 font-medium mb-12">We're building this feature to help you scale faster. Stay tuned for updates!</p>
      
      <div class="space-y-4">
        <div v-for="i in 3" :key="i" class="h-4 bg-gray-50 rounded-full w-full animate-pulse"></div>
        <div class="h-4 bg-gray-50 rounded-full w-2/3 mx-auto animate-pulse"></div>
      </div>

      <router-link to="/agent/dashboard" class="mt-12 inline-flex items-center gap-2 text-xs font-black text-blue-600 uppercase tracking-widest hover:underline">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
        Return to Dashboard
      </router-link>
    </div>
  </div>
</template>

<script setup>
</script>
