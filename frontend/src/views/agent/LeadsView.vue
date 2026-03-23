<template>
  <div class="space-y-6">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 px-2">
      <div>
        <h1 class="text-2xl font-extrabold text-gray-900 uppercase tracking-tighter">Lead Directory</h1>
        <p class="text-gray-400 text-[10px] font-black tracking-widest uppercase truncate max-w-[200px] sm:max-w-none">Comprehensive list of all opportunities</p>
      </div>
      <div class="flex items-center gap-2 w-full sm:w-auto">
        <button @click="showImport = true" class="px-4 py-2 bg-blue-50 text-blue-600 rounded-lg text-xs font-black uppercase tracking-widest hover:bg-blue-100 transition-all">Import</button>
        <button @click="showForm = true" class="px-4 py-2 bg-gray-900 text-white rounded-lg text-xs font-black uppercase tracking-widest hover:bg-black transition-all shadow-lg">+ Add Lead</button>
        <div class="flex-1 sm:flex-none relative ml-2">
          <input type="text" placeholder="Search leads..." class="w-full sm:w-64 px-4 py-2 pl-9 bg-white border border-gray-200 rounded-lg text-xs font-bold outline-none focus:ring-2 focus:ring-blue-500/10 transition-all" />
          <svg class="w-4 h-4 absolute left-3 top-2.5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
        </div>
        <button class="p-2 bg-white border border-gray-200 rounded-lg group hover:border-blue-600 transition-all">
          <AdjustmentsHorizontalIcon class="w-5 h-5 text-gray-400 group-hover:text-blue-600" />
        </button>
      </div>
    </div>

    <!-- Table Container -->
    <div class="bg-white rounded-3xl border border-gray-100 shadow-sm overflow-hidden">
      <div class="overflow-x-auto">
        <table class="w-full text-left">
          <thead>
            <tr class="bg-gray-50/50 border-b border-gray-50">
              <th v-for="col in columns" :key="col" class="px-8 py-5 text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ col }}</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-50">
            <tr v-for="lead in leads" :key="lead.id" class="hover:bg-gray-50/30 transition-all cursor-pointer group" @click="openLead(lead)">
              <td class="px-8 py-5">
                <div class="flex items-center gap-4">
                  <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center font-bold text-blue-600 group-hover:bg-[#0F1117] group-hover:text-white transition-all">
                    {{ lead.name.charAt(0) }}
                  </div>
                  <div>
                    <p class="text-sm font-bold text-gray-900">{{ lead.name }}</p>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter">{{ lead.source || 'Website' }}</p>
                  </div>
                </div>
              </td>
              <td class="px-8 py-5">
                <p class="text-xs font-bold text-gray-700">{{ lead.phone }}</p>
                <p class="text-[10px] text-gray-400">{{ lead.location || 'Gurgaon' }}</p>
              </td>
              <td class="px-8 py-5">
                <p class="text-xs font-black text-gray-900">₹ {{ (lead.budgetMax / 100000).toFixed(1) }}L</p>
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">{{ lead.configuration || '3 BHK' }}</p>
              </td>
              <td class="px-8 py-5">
                <Badge :type="getStatusType(lead.status)">{{ lead.status }}</Badge>
              </td>
              <td class="px-8 py-5">
                <div class="flex items-center gap-3">
                  <span :class="['w-8 h-8 rounded-lg flex items-center justify-center text-xs font-black', lead.score > 8 ? 'bg-red-50 text-red-600' : 'bg-gray-100 text-gray-500']">
                    {{ lead.score || 'N/A' }}
                  </span>
                  <div class="flex -space-x-2">
                    <div class="w-6 h-6 rounded-full border-2 border-white bg-blue-500 text-[8px] flex items-center justify-center font-black text-white">AK</div>
                  </div>
                </div>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      
      <!-- Pagination -->
      <div class="px-8 py-4 border-t border-gray-50 flex items-center justify-between">
        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest uppercase">Showing 1-{{ leads.length }} of {{ leads.length }} leads</p>
      </div>
    </div>

    <!-- Modals -->
    <LeadForm :show="showForm" @close="showForm = false" />
    <CSVImport :show="showImport" @close="showImport = false" @success="handleImportSuccess" />
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import { useRouter } from 'vue-router'
import { useLeadsStore } from '@/stores/leads'
import { storeToRefs } from 'pinia'
import { AdjustmentsHorizontalIcon } from '@heroicons/vue/24/outline'
import Badge from '@/components/ui/Badge.vue'
import LeadForm from '@/components/leads/LeadForm.vue'
import CSVImport from '@/components/leads/CSVImport.vue'

const showForm = ref(false)
const showImport = ref(false)
const router = useRouter()
const leadsStore = useLeadsStore()
const { leads } = storeToRefs(leadsStore)

const columns = ['Lead Details', 'Contact & Area', 'Budget & Unit', 'Pipeline Stage', 'AI Score']

onMounted(() => {
  leadsStore.initRealtime()
})

onUnmounted(() => {
  leadsStore.stopRealtime()
})

const getStatusType = (status) => {
  switch (status) {
    case 'booking': return 'success'
    case 'negotiation': return 'warning'
    case 'site_visit': return 'primary'
    default: return 'secondary'
  }
}

const openLead = (lead) => {
  const authStore = useAuthStore()
  const basePath = authStore.userRole === 'admin' ? '/admin' : '/agent'
  router.push(`${basePath}/leads/${lead.id}`)
}
</script>
