<template>
  <div class="px-8 pb-12">
    <h2 class="text-xs font-black text-gray-400 uppercase tracking-[0.2em] mb-6">Message History</h2>
    
    <div v-if="loading" class="space-y-4">
      <div v-for="i in 3" :key="i" class="h-24 bg-gray-50 rounded-2xl animate-pulse"></div>
    </div>
    
    <div v-else-if="messages.length === 0" class="text-center py-12 bg-gray-50/50 rounded-3xl border border-dashed border-gray-200">
      <p class="text-[10px] font-black text-gray-300 uppercase tracking-widest italic">No outreach history found</p>
    </div>

    <div v-else class="space-y-6">
      <div v-for="msg in messages" :key="msg.id" class="p-6 bg-white border border-gray-50 rounded-2xl shadow-sm hover:border-gray-100 transition-all">
        <div class="flex justify-between items-start mb-3">
          <span class="text-[9px] font-black text-blue-600 uppercase tracking-widest bg-blue-50 px-2 py-0.5 rounded">{{ msg.type.replace('_', ' ') }}</span>
          <span class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">{{ formatDate(msg.createdAt) }}</span>
        </div>
        <p class="text-xs font-medium text-gray-700 leading-relaxed">{{ msg.message }}</p>
        <div class="flex justify-end mt-3">
          <div class="flex gap-0.5">
            <CheckIcon v-for="i in 2" :key="i" class="w-3 h-3 text-emerald-500" />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { CheckIcon } from '@heroicons/vue/20/solid'

const props = defineProps({
  messages: Array,
  loading: Boolean
})

const formatDate = (date) => {
  if (!date) return ''
  const ts = date?.toDate ? date.toDate() : new Date(date)
  return ts.toLocaleString('en-IN', {
    day: 'numeric',
    month: 'short',
    hour: '2-digit',
    minute: '2-digit'
  })
}
</script>
