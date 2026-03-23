<template>
  <div class="space-y-8">
    <div class="flex justify-between items-center mb-12">
      <div class="text-left">
        <h1 class="text-4xl font-black text-gray-900 tracking-tighter uppercase mb-2">Builders & Partners</h1>
        <p class="text-gray-400 text-xs font-bold uppercase tracking-[0.2em] italic">Manage your developer relationships</p>
      </div>
      <button class="bg-gray-900 text-white px-8 py-4 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-black shadow-xl transition-all">
        + Add Builder
      </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 text-left">
      <div v-for="builder in builders" :key="builder.id"
        class="bg-white rounded-[40px] p-8 border border-gray-100 shadow-sm hover:shadow-2xl transition-all group">
        <div class="flex justify-between items-start mb-8">
          <div class="w-16 h-16 bg-gray-50 rounded-2xl flex items-center justify-center group-hover:bg-blue-50 transition-colors">
            <BuildingOffice2Icon class="w-8 h-8 text-gray-300 group-hover:text-blue-600" />
          </div>
          <span class="px-4 py-1.5 bg-gray-50 rounded-full text-[9px] font-black uppercase tracking-widest text-gray-400 group-hover:bg-blue-50 group-hover:text-blue-600">
            {{ builder.brokeragePercent }}% Brokerage
          </span>
        </div>

        <h3 class="text-xl font-black text-gray-900 mb-2 uppercase tracking-tight">{{ builder.name }}</h3>
        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-8">{{ builder.poc }} • {{ builder.phone }}</p>

        <div class="grid grid-cols-2 gap-4 pt-8 border-t border-gray-50">
          <div>
            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Due</p>
            <p class="text-xs font-black text-gray-900">₹{{ (builder.totalDue / 100000).toFixed(1) }}L</p>
          </div>
          <div class="text-right">
            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Received</p>
            <p class="text-xs font-black text-emerald-600">₹{{ (builder.totalPaid / 100000).toFixed(1) }}L</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { BuildingOffice2Icon } from '@heroicons/vue/24/outline'

const builders = [
  { id: 1, name: 'Godrej Properties', poc: 'Karan Mehra', phone: '98273 11223', brokeragePercent: 2, totalDue: 1850000, totalPaid: 4200000 },
  { id: 2, name: 'Lodha Group', poc: 'Sneha Rao', phone: '91223 88445', brokeragePercent: 3, totalDue: 1240000, totalPaid: 1560000 },
  { id: 3, name: 'DLF Limited', poc: 'Rahul Varma', phone: '99882 11002', brokeragePercent: 2.5, totalDue: 540000, totalPaid: 8900000 }
]
</script>
