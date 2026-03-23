<template>
  <div class="h-full bg-gray-50 overflow-y-auto">
    <div class="p-8 md:p-12 max-w-7xl mx-auto w-full">
      <div class="flex justify-between items-center mb-16">
        <div class="text-left">
          <h1 class="text-4xl font-black text-gray-900 tracking-tighter uppercase mb-2">Commissions</h1>
          <p class="text-gray-400 text-xs font-bold uppercase tracking-[0.2em] italic">Track your brokerage pipeline</p>
        </div>
        <div class="flex gap-4">
          <button class="px-6 py-4 bg-white border border-gray-100 rounded-2xl text-[10px] font-black uppercase tracking-widest text-gray-400 hover:text-gray-900 transition-all">Filter</button>
          <button class="px-8 py-4 bg-gray-900 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-black shadow-xl transition-all">+ Log Deal</button>
        </div>
      </div>

      <!-- Financial Summary Cards -->
      <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-12 text-left">
        <div v-for="stat in stats" :key="stat.label" class="bg-white p-8 rounded-[40px] border border-gray-100 shadow-sm">
          <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-4">{{ stat.label }}</p>
          <p class="text-2xl font-black text-gray-900">₹{{ stat.value }}</p>
          <div class="mt-4 flex items-center gap-2">
            <span :class="stat.trend > 0 ? 'text-emerald-500' : 'text-blue-500'" class="text-[10px] font-black uppercase tracking-tighter">
              {{ stat.trend > 0 ? '+' : '' }}{{ stat.trend }}%
            </span>
            <span class="text-[9px] font-bold text-gray-300 uppercase tracking-widest italic">vs last month</span>
          </div>
        </div>
      </div>

      <!-- Commissions Table -->
      <div class="bg-white rounded-[40px] border border-gray-100 shadow-sm overflow-hidden text-left">
        <table class="w-full">
          <thead>
            <tr class="border-b border-gray-50">
              <th class="px-10 py-8 text-[10px] font-black text-gray-400 uppercase tracking-widest text-left">Deal & Property</th>
              <th class="px-10 py-8 text-[10px] font-black text-gray-400 uppercase tracking-widest text-left text-center">Amount Due</th>
              <th class="px-10 py-8 text-[10px] font-black text-gray-400 uppercase tracking-widest text-left text-center">Paid</th>
              <th class="px-10 py-8 text-[10px] font-black text-gray-400 uppercase tracking-widest text-left text-center">Status</th>
              <th class="px-10 py-8 text-[10px] font-black text-gray-400 uppercase tracking-widest text-right">Actions</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-50">
            <tr v-for="comm in store.commissions" :key="comm.id" class="group hover:bg-gray-50/50 transition-all">
              <td class="px-10 py-8">
                <div class="space-y-1">
                  <p class="text-sm font-black text-gray-900 uppercase tracking-tight">{{ comm.propertyTitle }}</p>
                  <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest italic">{{ comm.leadName }}</p>
                </div>
              </td>
              <td class="px-10 py-8 text-center">
                <p class="text-xs font-black text-gray-900">₹{{ comm.commissionAmount / 100000 }}L</p>
              </td>
              <td class="px-10 py-8 text-center">
                <p class="text-xs font-black text-emerald-600">₹{{ (comm.paidAmount || 0) / 100000 }}L</p>
              </td>
              <td class="px-10 py-8 text-center">
                <span :class="statusClass(comm.status)" class="px-4 py-1.5 rounded-full text-[9px] font-black uppercase tracking-widest">
                  {{ comm.status }}
                </span>
              </td>
              <td class="px-10 py-8 text-right">
                <button @click="openPaymentModal(comm)" class="px-6 py-2 bg-gray-900 text-white rounded-xl text-[10px] font-black uppercase tracking-widest">Log Payment</button>
              </td>
            </tr>
          </tbody>
        </table>
        
        <div v-if="store.commissions.length === 0" class="p-20 flex flex-col items-center justify-center space-y-4">
          <BanknotesIcon class="w-12 h-12 text-gray-200" />
          <p class="text-xs font-black text-gray-400 uppercase tracking-widest">No commissions recorded yet</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { onMounted } from 'vue'
import { useBuildersStore } from '@/stores/builders'
import { BanknotesIcon } from '@heroicons/vue/24/outline'

const store = useBuildersStore()

const stats = [
  { label: 'Total Pipeline', value: '42.5L', trend: 12 },
  { label: 'Total Collected', value: '18.2L', trend: 8 },
  { label: 'Overdue 30d', value: '4.8L', trend: -2 },
  { label: 'Projected', value: '12.4L', trend: 15 }
]

onMounted(() => {
  store.fetchCommissions()
})

const statusClass = (status) => {
  if (status === 'paid') return 'bg-emerald-500/10 text-emerald-500'
  if (status === 'partial') return 'bg-blue-500/10 text-blue-500'
  return 'bg-red-500/10 text-red-500'
}

const openPaymentModal = (comm) => {
  // Logic to open payment modal
}
</script>
