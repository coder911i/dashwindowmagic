<template>
  <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 hover:border-blue-100 group transition-all flex flex-col gap-5 cursor-pointer relative overflow-hidden">
    <!-- Score Accent -->
    <div 
      class="absolute top-0 right-0 w-12 h-12 flex items-center justify-center transform rotate-45 translate-x-6 -translate-y-6"
      :class="lead.score > 8 ? 'bg-red-600' : 'bg-amber-400'"
    >
      <span class="text-[8px] font-black text-white -rotate-45 mt-4 translate-y-1">AI HOT</span>
    </div>

    <!-- User Header -->
    <div class="flex items-center gap-4">
      <div class="w-12 h-12 rounded-2xl bg-[#F9FAFB] flex items-center justify-center font-black text-gray-900 text-lg border border-gray-100 group-hover:bg-[#0F1117] group-hover:text-white transition-all">
        {{ lead.name.charAt(0) }}
      </div>
      <div class="flex-1 min-w-0">
        <h3 class="text-sm font-black text-gray-900 group-hover:text-blue-600 truncate transition-colors">{{ lead.name }}</h3>
        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest truncate">{{ lead.project || 'New Inquiry' }}</p>
      </div>
    </div>

    <!-- Requirements Grid -->
    <div class="grid grid-cols-2 gap-3">
      <div class="px-3 py-2 bg-gray-50 rounded-xl border border-gray-50 group-hover:bg-white transition-colors">
        <p class="text-[8px] font-black text-gray-400 uppercase mb-0.5 tracking-tighter">Budget</p>
        <p class="text-[11px] font-bold text-gray-800">₹ {{ formatPrice(lead.budgetMax) }}</p>
      </div>
      <div class="px-3 py-2 bg-gray-50 rounded-xl border border-gray-50 group-hover:bg-white transition-colors text-right">
        <p class="text-[8px] font-black text-gray-400 uppercase mb-0.5 tracking-tighter">Config</p>
        <p class="text-[11px] font-bold text-gray-800">{{ lead.configuration || '3 BHK' }}</p>
      </div>
    </div>

    <!-- Footer Meta -->
    <div class="flex items-center justify-between mt-1 pt-4 border-t border-gray-50">
      <div class="flex gap-1.5 overflow-hidden">
        <Badge v-if="lead.source" type="secondary" class="!px-1.5 !py-0.5 !text-[8px] truncate max-w-[80px]">{{ lead.source }}</Badge>
        <Badge type="primary" class="!px-1.5 !py-0.5 !text-[8px]">{{ lead.location || 'Mumbai' }}</Badge>
      </div>
      <p class="text-[9px] font-bold text-gray-300 uppercase whitespace-nowrap">{{ timeAgo(lead.createdAt) }}</p>
    </div>
  </div>
</template>

<script setup>
import Badge from '@/components/ui/Badge.vue'

const props = defineProps({
  lead: {
    type: Object,
    required: true
  }
})

const formatPrice = (val) => {
  if (!val) return 'N/A'
  if (val >= 10000000) return (val / 10000000).toFixed(1) + ' Cr'
  if (val >= 100000) return (val / 100000).toFixed(1) + ' L'
  return val.toLocaleString('en-IN')
}

const timeAgo = (date) => {
  if (!date) return 'Recently'
  const ts = date?.toDate ? date.toDate() : new Date(date)
  const seconds = Math.floor((new Date() - ts) / 1000)
  
  if (seconds < 3600) return Math.floor(seconds/60) + 'm ago'
  if (seconds < 86400) return Math.floor(seconds/3600) + 'h ago'
  return Math.floor(seconds/86400) + 'd ago'
}
</script>
