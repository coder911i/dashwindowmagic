<template>
  <div class="flex gap-4 p-6 bg-white border-b border-gray-100 overflow-x-auto scrollbar-hide">
    <div class="relative min-w-[200px]">
      <input 
        v-model="filters.search" 
        type="text" 
        placeholder="Search enquiry..." 
        class="w-full pl-9 pr-4 py-2 bg-gray-50 border border-gray-100 rounded-xl text-xs font-bold outline-none focus:ring-2 focus:ring-blue-500/10 transition-all"
      />
      <MagnifyingGlassIcon class="w-4 h-4 absolute left-3 top-2.5 text-gray-300" />
    </div>

    <select v-model="filters.source" class="px-4 py-2 bg-gray-50 border border-gray-100 rounded-xl text-xs font-black uppercase tracking-widest outline-none focus:ring-2 focus:ring-blue-500/10 hover:bg-white transition-all">
      <option value="">All Sources</option>
      <option v-for="s in sources" :key="s" :value="s">{{ s }}</option>
    </select>

    <select v-model="filters.status" class="px-4 py-2 bg-gray-50 border border-gray-100 rounded-xl text-xs font-black uppercase tracking-widest outline-none focus:ring-2 focus:ring-blue-500/10 hover:bg-white transition-all">
      <option value="">All Status</option>
      <option v-for="s in statuses" :key="s" :value="s.toLowerCase()">{{ s }}</option>
    </select>

    <button @click="reset" class="px-4 py-2 text-[10px] font-black text-gray-400 uppercase tracking-widest hover:text-blue-600 transition-all">
      Reset
    </button>
  </div>
</template>

<script setup>
import { reactive, watch } from 'vue'
import { MagnifyingGlassIcon } from '@heroicons/vue/24/outline'

const sources = ['Google Ads', '99acres', 'MagicBricks', 'Website', 'Referral', 'Manual']
const statuses = ['New', 'Contacted', 'Visit Booked', 'Closed', 'Cold']

const emit = defineEmits(['filter'])

const filters = reactive({
  search: '',
  source: '',
  status: ''
})

watch(filters, (newVal) => {
  emit('filter', { ...newVal })
}, { deep: true })

const reset = () => {
  filters.search = ''
  filters.source = ''
  filters.status = ''
}
</script>
