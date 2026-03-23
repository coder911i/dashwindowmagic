<template>
  <div class="flex items-center gap-1">
    <span v-if="type === 'hot'" class="w-2.5 h-2.5 rounded-full bg-red-600 shadow-[0_0_8px_rgba(220,38,38,0.5)]"></span>
    <span v-else-if="type === 'warm'" class="w-2.5 h-2.5 rounded-full bg-amber-500 shadow-[0_0_8px_rgba(245,158,11,0.5)]"></span>
    <span v-else class="w-2.5 h-2.5 rounded-full bg-gray-400"></span>
    <span class="text-[10px] font-black uppercase text-gray-500 tracking-tighter ml-1">
      {{ type }}
    </span>
  </div>
</template>

<script setup>
defineProps({
  type: String // hot, warm, cold
})
</script>
