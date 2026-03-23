<template>
  <div class="p-8 max-w-7xl mx-auto space-y-12">
    <div class="flex justify-between items-end text-left">
      <div>
        <h1 class="text-4xl font-black text-gray-900 tracking-tighter uppercase mb-2">RERA Registry</h1>
        <p class="text-gray-400 text-xs font-bold uppercase tracking-[0.2em] italic">Compliance & Regulatory Tracking</p>
      </div>
      <div class="flex gap-4">
        <div class="text-right">
          <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Total Verified</p>
          <p class="text-2xl font-black text-gray-900">{{ records.length }}</p>
        </div>
        <div class="w-[1px] h-10 bg-gray-100"></div>
        <div class="text-right">
          <p class="text-[10px] font-black text-red-400 uppercase tracking-widest mb-1">Alerts</p>
          <p class="text-2xl font-black text-red-600">{{ alertsCount }}</p>
        </div>
      </div>
    </div>

    <RERAValidator @save="handleLog" />

    <div class="bg-white rounded-[32px] border border-gray-100 shadow-sm overflow-hidden text-left">
      <div class="p-8 border-b border-gray-50 flex items-center justify-between">
        <h3 class="text-xs font-black text-gray-900 uppercase tracking-[0.2em]">Verified Properties</h3>
        <button class="text-[10px] font-black text-blue-600 uppercase tracking-widest hover:underline">Export Logs</button>
      </div>
      
      <div v-if="loading" class="p-12 space-y-4">
        <div v-for="i in 5" :key="i" class="h-16 bg-gray-50 rounded-2xl animate-pulse"></div>
      </div>
      
      <RERATable v-else :records="records" />
    </div>
  </div>
</template>

<script setup>
import { computed, onMounted } from 'vue'
import { useReraStore } from '@/stores/rera'
import { storeToRefs } from 'pinia'
import RERAValidator from '@/components/rera/RERAValidator.vue'
import RERATable from '@/components/rera/RERATable.vue'

const reraStore = useReraStore()
const { records, loading } = storeToRefs(reraStore)

onMounted(() => {
  reraStore.fetchRecords()
})

const alertsCount = computed(() => {
  return records.value.filter(r => {
    if (!r.expiryDate) return false
    const diff = new Date(r.expiryDate) - new Date()
    return diff < (60 * 1000 * 60 * 24 * 60) // 60 days
  }).length
})

const handleLog = async (form) => {
  if (confirm(`Save RERA record for ${form.reraNumber}?`)) {
    await reraStore.addRecord({
      ...form,
      projectName: 'New Project Entry',
      expiryDate: new Date(Date.now() + 365 * 24 * 60 * 60 * 1000).toISOString().split('T')[0]
    })
  }
}
</script>
