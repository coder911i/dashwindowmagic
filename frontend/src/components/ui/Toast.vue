<template>
  <div :class="[
    'p-4 rounded-2xl shadow-2xl flex items-center gap-4 border transition-all duration-300',
    type === 'success' ? 'bg-white border-emerald-100' : 'bg-white border-red-100'
  ]">
    <div :class="['w-10 h-10 rounded-xl flex items-center justify-center', 
      type === 'success' ? 'bg-emerald-50 text-emerald-600' : 'bg-red-50 text-red-600']">
      <CheckCircleIcon v-if="type === 'success'" class="w-6 h-6" />
      <ExclamationCircleIcon v-else class="w-6 h-6" />
    </div>
    
    <div>
      <p class="text-xs font-black text-gray-900 uppercase tracking-tight">{{ message }}</p>
      <p v-if="description" class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-0.5">{{ description }}</p>
    </div>
    
    <button @click="$emit('close')" class="ml-auto text-gray-300 hover:text-gray-900">
      <XMarkIcon class="w-4 h-4" />
    </button>
  </div>
</template>

<script setup>
import { CheckCircleIcon, ExclamationCircleIcon, XMarkIcon } from '@heroicons/vue/24/outline'

defineProps({
  message: String,
  description: String,
  type: { type: String, default: 'success' }
})
defineEmits(['close'])
</script>
