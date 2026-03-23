<template>
  <div class="overflow-x-auto custom-scrollbar">
    <table class="w-full text-left border-collapse">
      <thead>
        <tr class="border-b border-gray-50">
          <th class="p-6">
            <input 
              type="checkbox" 
              class="w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500 cursor-pointer"
              :checked="isAllSelected"
              @change="$emit('toggleSelectAll')"
            />
          </th>
          <th v-for="col in columns" :key="col" class="p-6 text-[10px] font-black text-gray-400 uppercase tracking-widest">
            {{ col }}
          </th>
        </tr>
      </thead>
      <tbody>
        <tr 
          v-for="item in enquiries" :key="item.id"
          class="group border-b border-gray-50 hover:bg-blue-50/30 transition-all"
        >
          <td class="p-6">
            <input 
              type="checkbox" 
              class="w-4 h-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500 cursor-pointer"
              :checked="selectedIds.includes(item.id)"
              @change="$emit('toggleSelect', item.id)"
            />
          </td>
          <td class="p-6">
            <p class="text-[11px] font-black text-gray-900 tracking-tight">{{ formatDate(item.createdAt) }}</p>
          </td>
          <td class="p-6">
            <div class="flex items-center gap-2">
              <span class="text-[12px] font-black text-gray-900 tracking-tight">{{ item.leadName }}</span>
              <span v-if="item.isDuplicate" class="bg-orange-100 text-orange-600 px-1.5 py-0.5 rounded-md text-[8px] font-black uppercase tracking-tighter">Duplicate ⚠️</span>
            </div>
            <p class="text-[10px] font-bold text-gray-400 tracking-tighter">{{ item.phone }}</p>
          </td>
          <td class="p-6">
            <p class="text-[11px] font-black text-gray-900 tracking-tight">{{ item.propertyName || 'N/A' }}</p>
            <p class="text-[10px] font-bold text-gray-400 tracking-tighter">₹ {{ (item.budget / 100000).toFixed(1) }}L</p>
          </td>
          <td class="p-6">
            <span :class="['px-2 py-1 rounded-lg text-[9px] font-black uppercase tracking-widest', getSourceClass(item.source)]">
              {{ item.source }}
            </span>
          </td>
          <td class="p-6">
            <div class="flex items-center gap-2">
              <div class="w-6 h-6 rounded-full bg-gray-100 flex items-center justify-center text-[8px] font-black">
                {{ item.agentName?.charAt(0) || '?' }}
              </div>
              <span class="text-[10px] font-bold text-gray-600 truncate max-w-[80px]">{{ item.agentName || 'Unassigned' }}</span>
            </div>
          </td>
          <td class="p-6">
            <span :class="['px-2 py-1 rounded-lg text-[9px] font-black uppercase tracking-widest', getStatusClass(item.status)]">
              {{ item.status }}
            </span>
          </td>
          <td class="p-6">
            <button class="p-2 hover:bg-gray-100 rounded-lg transition-all text-gray-400 hover:text-blue-600">
              <EllipsisHorizontalIcon class="w-5 h-5" />
            </button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script setup>
import { EllipsisHorizontalIcon } from '@heroicons/vue/24/outline'

defineProps({
  enquiries: Array,
  selectedIds: Array,
  isAllSelected: Boolean
})

const columns = ['Date', 'Lead Info', 'Property', 'Source', 'Agent', 'Status', '']

const formatDate = (date) => {
  if (!date) return ''
  const ts = date?.toDate ? date.toDate() : new Date(date)
  return ts.toLocaleDateString('en-IN', { day: '2-digit', month: 'short' })
}

const getSourceClass = (src) => {
  const map = {
    'Google Ads': 'bg-blue-50 text-blue-600',
    '99acres': 'bg-orange-50 text-orange-600',
    'MagicBricks': 'bg-red-50 text-red-600',
    'Website': 'bg-purple-50 text-purple-600',
    'Referral': 'bg-green-50 text-green-600'
  }
  return map[src] || 'bg-gray-50 text-gray-400'
}

const getStatusClass = (status) => {
  const map = {
    'new': 'bg-blue-50 text-blue-600',
    'contacted': 'bg-amber-50 text-amber-600',
    'visit booked': 'bg-purple-50 text-purple-600',
    'closed': 'bg-green-50 text-green-600',
    'cold': 'bg-gray-100 text-gray-400'
  }
  return map[status] || 'bg-gray-50 text-gray-400'
}
</script>
