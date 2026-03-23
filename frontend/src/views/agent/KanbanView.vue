<template>
  <div class="h-[calc(100vh-12rem)] flex flex-col gap-6">
    <!-- Header -->
    <div class="flex justify-between items-center px-2">
      <div>
        <h1 class="text-2xl font-extrabold text-gray-900 uppercase tracking-tighter">My Pipeline</h1>
        <p class="text-gray-400 text-[10px] font-black tracking-widest uppercase">Drag and drop leads to update status</p>
      </div>
      <div class="flex items-center gap-2">
        <button class="px-4 py-2 border border-gray-200 rounded-lg text-xs font-bold text-gray-600 bg-white hover:bg-gray-50 flex items-center gap-2 transition-all">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" /></svg>
          Filter
        </button>
        <button @click="showAddModal = true" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-xs font-bold hover:bg-blue-700 flex items-center gap-2 transition-all shadow-lg shadow-blue-500/20">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
          Add New Lead
        </button>
      </div>
    </div>

    <!-- Kanban Board -->
    <div class="flex-1 flex gap-4 overflow-x-auto pb-4 custom-scrollbar px-2">
      <div v-for="stage in stages" :key="stage.id" class="flex-1 min-w-[320px] flex flex-col gap-4">
        <!-- Column Header -->
        <div class="flex items-center justify-between px-2 bg-gray-50/50 p-3 rounded-xl border border-gray-100">
          <div class="flex items-center gap-2">
            <span :class="['w-2 h-2 rounded-full', stage.dotColor]"></span>
            <span class="text-xs font-black text-gray-900 uppercase tracking-tight">{{ stage.name }}</span>
            <span class="px-1.5 py-0.5 rounded-full bg-gray-200 text-gray-600 text-[10px] font-bold">{{ stageLeads(stage.id).length }}</span>
          </div>
          <p class="text-[10px] font-bold text-gray-400">₹ {{ formatStageValue(stage.id) }}L</p>
        </div>

        <!-- Draggable List (Simulated) -->
        <div class="flex-1 flex flex-col gap-3">
          <LeadCard 
            v-for="lead in stageLeads(stage.id)" 
            :key="lead.id" 
            :lead="lead" 
            @click="openLead(lead)" 
          />
          
          <!-- Empty State -->
          <div v-if="stageLeads(stage.id).length === 0" class="h-32 rounded-xl border-2 border-dashed border-gray-100 flex items-center justify-center text-[10px] font-bold text-gray-300 uppercase italic">
            No leads in this stage
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import { useRouter } from 'vue-router'
import { useLeadsStore } from '@/stores/leads'
import { storeToRefs } from 'pinia'
import LeadCard from '@/components/leads/LeadCard.vue'

const router = useRouter()
const leadsStore = useLeadsStore()
const { leads } = storeToRefs(leadsStore)
const showAddModal = ref(false)

const stages = [
  { id: 'new', name: 'New Leads', dotColor: 'bg-blue-500' },
  { id: 'contacted', name: 'Contacted', dotColor: 'bg-indigo-500' },
  { id: 'site_visit', name: 'Site Visit', dotColor: 'bg-amber-500' },
  { id: 'negotiation', name: 'Negotiation', dotColor: 'bg-emerald-500' },
  { id: 'booking', name: 'Booking Done', dotColor: 'bg-red-600' }
]

onMounted(() => {
  leadsStore.initRealtime()
})

onUnmounted(() => {
  leadsStore.stopRealtime()
})

const stageLeads = (stageId) => {
  return leads.value.filter(l => l.status === stageId)
}

const formatStageValue = (stageId) => {
  const sum = stageLeads(stageId).reduce((acc, l) => acc + (parseFloat(l.budgetMax) || 0), 0)
  return (sum / 100000).toFixed(1)
}

const openLead = (lead) => {
  router.push(`/agent/leads/${lead.id}`)
}
</script>
