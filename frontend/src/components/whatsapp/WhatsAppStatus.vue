<template>
  <div class="flex items-center gap-1.5 px-2 py-1 bg-gray-50 rounded-lg">
    <span class="text-[9px] font-black uppercase text-gray-400 tracking-tighter">{{ status }}</span>
    <div class="flex -space-x-1">
      <CheckIcon v-if="status === 'sent' || status === 'delivered' || status === 'read'" 
        class="w-3 h-3" :class="status === 'read' ? 'text-blue-500' : 'text-gray-400'" />
      <CheckIcon v-if="status === 'delivered' || status === 'read'" 
        class="w-3 h-3" :class="status === 'read' ? 'text-blue-500' : 'text-gray-400'" />
    </div>
  </div>
</template>

<script setup>
import { CheckIcon } from '@heroicons/vue/20/solid'

defineProps({
  status: {
    type: String,
    default: 'sent' // sent, delivered, read
  }
})
</script>
