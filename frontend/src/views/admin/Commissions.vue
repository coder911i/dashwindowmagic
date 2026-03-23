<template>
  <div class="space-y-12">
    <div class="flex justify-between items-center text-left">
      <div>
        <h1 class="text-4xl font-black text-gray-900 tracking-tighter uppercase mb-2">Commissions</h1>
        <p class="text-gray-400 text-xs font-bold uppercase tracking-[0.2em] italic">Track your revenue pipeline</p>
      </div>
      <div class="flex gap-4">
        <div class="bg-white px-6 py-4 rounded-2xl border border-gray-100 shadow-sm flex flex-col justify-center">
            <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Pipeline</span>
            <span class="text-lg font-black text-gray-900">₹4.2Cr</span>
        </div>
        <div class="bg-[#0F1117] px-6 py-4 rounded-2xl shadow-xl flex flex-col justify-center">
            <span class="text-[9px] font-black text-gray-300/50 uppercase tracking-widest mb-1">Total Due</span>
            <span class="text-lg font-black text-white">₹32.4L</span>
        </div>
      </div>
    </div>

    <!-- Buckets -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
      <div v-for="bucket in buckets" :key="bucket.label" 
        class="bg-white p-8 rounded-[32px] border border-gray-100 shadow-sm hover:shadow-xl transition-all text-left">
        <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-2">{{ bucket.label }}</p>
        <div class="flex items-baseline gap-2">
            <p class="text-2xl font-black text-gray-900">₹{{ bucket.value }}L</p>
            <span class="text-[10px] font-black text-red-500">{{ bucket.count }} deals</span>
        </div>
      </div>
    </div>

    <!-- Commissions Table -->
    <DataTable :columns="columns" :data="commissions">
      <template #status="{ row }">
        <span 
          class="text-[9px] font-black px-3 py-1 rounded-full uppercase tracking-widest"
          :class="row.status === 'Overdue' ? 'bg-red-50 text-red-500' : 'bg-emerald-50 text-emerald-500'"
        >
          {{ row.status }}
        </span>
      </template>
      <template #action>
        <button class="text-[10px] font-black text-blue-600 uppercase tracking-widest hover:underline">Log Payment</button>
      </template>
    </DataTable>
  </div>
</template>

<script setup>
import DataTable from '@/components/ui/DataTable.vue'

const buckets = [
  { label: 'Current', value: 12.4, count: 5 },
  { label: '30 Days Overdue', value: 8.2, count: 3 },
  { label: '60 Days Overdue', value: 5.5, count: 2 },
  { label: '90+ Days Overdue', value: 6.3, count: 2 }
]

const columns = [
  { key: 'deal', label: 'Deal / Lead' },
  { key: 'builder', label: 'Builder' },
  { key: 'amount', label: 'Amount Due' },
  { key: 'dueDate', label: 'Due Date' },
  { key: 'status', label: 'Status' },
  { key: 'action', label: 'Action' }
]

const commissions = [
  { deal: 'Rahul Sharma - 3BHK Noida', builder: 'Godrej Properties', amount: '₹1,24,000', dueDate: '12 Mar 2024', status: 'Overdue' },
  { deal: 'Priya Gupta - 2BHK Whitefield', builder: 'Lodha Group', amount: '₹84,500', dueDate: '18 Apr 2024', status: 'Pending' },
  { deal: 'Amit Patel - Villa Hyderabad', builder: 'DLF Limited', amount: '₹3,40,000', dueDate: '05 Mar 2024', status: 'Overdue' }
]
</script>
