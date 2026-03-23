<template>
  <div class="h-screen w-full flex flex-col items-center justify-center bg-gray-50 p-8">
    <div class="relative mb-12">
      <div class="text-[200px] font-black text-gray-100 leading-none select-none">404</div>
      <div class="absolute inset-0 flex items-center justify-center">
        <div class="w-32 h-32 bg-white rounded-[40px] shadow-2xl flex items-center justify-center animate-bounce-slow">
          <MapIcon class="w-16 h-16 text-blue-600" />
        </div>
      </div>
    </div>
    
    <div class="text-center space-y-4 max-w-md">
      <h1 class="text-3xl font-black text-gray-900 uppercase tracking-tighter">Lost in the project?</h1>
      <p class="text-gray-400 text-xs font-bold uppercase tracking-widest leading-relaxed">
        The property address you're looking for doesn't exist in our current registry.
      </p>
      <div class="pt-8">
        <router-link to="/admin/dashboard" class="inline-block bg-gray-900 text-white px-12 py-5 rounded-[24px] text-xs font-black uppercase tracking-widest hover:bg-black transition-all shadow-xl shadow-gray-900/10">
          Back to Dashboard
        </router-link>
      </div>
    </div>
  </div>
</template>

<script setup>
import { MapIcon } from '@heroicons/vue/24/outline'
</script>

<style scoped>
@keyframes bounce-slow {
  0%, 100% { transform: translateY(0); }
  50% { transform: translateY(-20px); }
}
.animate-bounce-slow {
  animation: bounce-slow 4s infinite ease-in-out;
}
</style>
