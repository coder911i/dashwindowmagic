<template>
  <div class="flex flex-col items-center justify-center py-20 px-6 text-center">
    <div class="w-48 h-48 mb-8 relative">
      <div class="absolute inset-0 bg-blue-100 rounded-full animate-pulse opacity-30"></div>
      <div class="absolute inset-4 bg-white rounded-full flex items-center justify-center border-4 border-gray-50">
        <component :is="icon" class="w-16 h-16 text-gray-300" />
      </div>
    </div>
    
    <h3 class="text-xl font-black text-gray-900 mb-2 uppercase tracking-tighter">{{ title }}</h3>
    <p class="text-xs font-bold text-gray-400 uppercase tracking-widest max-w-xs mb-8">{{ description }}</p>
    
    <button v-if="actionLabel" @click="$emit('action')" 
      class="bg-gray-900 text-white px-8 py-4 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-black transition-all shadow-xl">
      {{ actionLabel }}
    </button>
  </div>
</template>

<script setup>
import { ArchiveBoxIcon } from '@heroicons/vue/24/outline'

defineProps({
  title: { type: String, default: 'No Data Found' },
  description: { type: String, default: 'We couldn\'t find any records matching your criteria.' },
  icon: { type: Object, default: ArchiveBoxIcon },
  actionLabel: String
})

defineEmits(['action'])
</script>
