<template>
  <div class="h-full flex flex-col bg-white border-r border-gray-100 overflow-hidden">
    <div class="p-6 border-b border-gray-50">
      <h2 class="text-xs font-black text-gray-400 uppercase tracking-[0.2em] mb-4">Select Lead</h2>
      <div class="relative">
        <input 
          v-model="search" 
          type="text" 
          placeholder="Search name or group..." 
          class="w-full pl-9 pr-4 py-3 bg-gray-50 border border-gray-100 rounded-xl text-xs font-bold outline-none focus:ring-2 focus:ring-blue-500/10 transition-all"
        />
        <MagnifyingGlassIcon class="w-4 h-4 absolute left-3 top-3.5 text-gray-300" />
      </div>
      
      <div class="flex gap-2 mt-4 overflow-x-auto pb-2 scrollbar-hide">
        <button 
          v-for="f in ['All', 'Hot', 'Warm', 'Cold']" :key="f"
          @click="filter = f"
          :class="['px-3 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-widest transition-all', filter === f ? 'bg-blue-600 text-white shadow-lg shadow-blue-500/20' : 'bg-gray-50 text-gray-400 hover:bg-gray-100']"
        >
          {{ f }}
        </button>
      </div>
    </div>

    <div class="flex-1 overflow-y-auto custom-scrollbar p-2">
      <div 
        v-for="lead in filteredLeads" :key="lead.id" 
        @click="$emit('select', lead)"
        :class="['flex items-center gap-4 p-4 rounded-2xl cursor-pointer transition-all mb-2 group', selectedId === lead.id ? 'bg-blue-50/50 border-l-4 border-blue-600' : 'hover:bg-gray-50 border-l-4 border-transparent']"
      >
        <div class="w-10 h-10 rounded-xl bg-[#0F1117] flex items-center justify-center font-black text-white text-xs">
          {{ lead.name.charAt(0) }}
        </div>
        <div class="flex-1 min-w-0">
          <div class="flex justify-between items-start">
            <p class="text-sm font-black text-gray-900 truncate tracking-tight">{{ lead.name }}</p>
            <LeadScoreBadge :score="lead.score" />
          </div>
          <p class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter truncate">{{ lead.project || 'General Inquiry' }}</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { MagnifyingGlassIcon } from '@heroicons/vue/24/outline'
import LeadScoreBadge from '@/components/ui/LeadScoreBadge.vue'

const props = defineProps({
  leads: Array,
  selectedId: String
})

const emit = defineEmits(['select'])

const search = ref('')
const filter = ref('All')

const filteredLeads = computed(() => {
  return props.leads.filter(l => {
    const matchesSearch = l.name.toLowerCase().includes(search.value.toLowerCase())
    if (filter.value === 'All') return matchesSearch
    if (filter.value === 'Hot') return matchesSearch && l.score >= 8
    if (filter.value === 'Warm') return matchesSearch && l.score >= 5 && l.score < 8
    return matchesSearch && l.score < 5
  })
})
</script>
