<template>
  <Transition name="slide-up">
    <div v-if="count > 0" class="fixed bottom-12 left-1/2 -translate-x-1/2 bg-[#0F1117] text-white px-8 py-4 rounded-3xl shadow-[0_20px_60px_-15px_rgba(0,0,0,0.5)] flex items-center gap-8 z-50 border border-white/10">
      <p class="text-[10px] font-black uppercase tracking-[0.2em] text-blue-400">
        {{ count }} Selected
      </p>

      <div class="h-6 w-[1px] bg-white/10"></div>

      <div class="flex gap-4">
        <button @click="$emit('action', { type: 'assign' })" class="flex items-center gap-2 text-[10px] font-black uppercase tracking-widest hover:text-blue-400 transition-all">
          <UserIcon class="w-4 h-4" /> Assign
        </button>
        <button @click="$emit('action', { type: 'status', value: 'contacted' })" class="flex items-center gap-2 text-[10px] font-black uppercase tracking-widest hover:text-blue-400 transition-all">
          <CheckBadgeIcon class="w-4 h-4" /> Mark Contacted
        </button>
        <button @click="$emit('action', { type: 'export' })" class="flex items-center gap-2 text-[10px] font-black uppercase tracking-widest hover:text-blue-400 transition-all">
          <ArrowDownTrayIcon class="w-4 h-4" /> Export
        </button>
        <button @click="$emit('action', { type: 'delete' })" class="flex items-center gap-2 text-[10px] font-black uppercase tracking-widest text-red-400 hover:text-red-300 transition-all">
          <TrashIcon class="w-4 h-4" /> Delete
        </button>
      </div>
    </div>
  </Transition>
</template>

<script setup>
import { UserIcon, CheckBadgeIcon, ArrowDownTrayIcon, TrashIcon } from '@heroicons/vue/24/solid'

defineProps({
  count: Number
})

defineEmits(['action'])
</script>

<style scoped>
.slide-up-enter-active, .slide-up-leave-active {
  transition: all 0.4s cubic-bezier(0.23, 1, 0.32, 1);
}
.slide-up-enter-from, .slide-up-leave-to {
  transform: translate(-50%, 100px);
  opacity: 0;
}
</style>
