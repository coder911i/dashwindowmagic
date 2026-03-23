<template>
  <div class="space-y-8">
    <div class="flex justify-between items-center">
      <div>
        <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">Marketing Campaigns</h1>
        <p class="text-gray-500 text-sm font-medium">Manage your meta and google ad integrations.</p>
      </div>
      <button class="bg-[#0F1117] text-white px-6 py-3 rounded-2xl text-[10px] font-black uppercase tracking-[0.2em] shadow-xl hover:translate-y-[-2px] transition-all">
        Create New Campaign
      </button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
      <div v-for="c in 3" :key="c" class="bg-white rounded-3xl border border-gray-100 p-8 shadow-sm hover:border-blue-100 transition-all">
        <div class="w-12 h-12 bg-blue-50 rounded-2xl flex items-center justify-center mb-6">
          <MegaphoneIcon class="w-6 h-6 text-blue-600" />
        </div>
        <h3 class="text-lg font-black text-gray-900 mb-2 uppercase tracking-tighter">Mumbai Luxury Residences</h3>
        <p class="text-xs font-medium text-gray-400 mb-6 uppercase tracking-widest">Active • Spent: ₹12.4k</p>
        <div class="flex justify-between items-end">
          <div>
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Total Leads</p>
            <p class="text-2xl font-black text-gray-900">124</p>
          </div>
          <button class="text-[10px] font-black text-blue-600 uppercase tracking-widest hover:underline">Manage</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { MegaphoneIcon } from '@heroicons/vue/24/outline'
</script>
