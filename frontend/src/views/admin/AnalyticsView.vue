<template>
  <div class="space-y-8">
    <div class="flex justify-between items-center">
      <div>
        <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">Agency Analytics</h1>
        <p class="text-gray-500 text-sm font-medium">Deep dive into your agency's funnel and performance.</p>
      </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
      <!-- Funnel Chart Placeholder -->
      <div class="bg-white rounded-3xl border border-gray-100 p-8 shadow-sm h-96 flex flex-col">
        <h3 class="text-sm font-black text-gray-900 uppercase tracking-widest mb-8">Sales Funnel</h3>
        <div class="flex-1 flex flex-col justify-center items-center gap-4">
          <div v-for="(stage, idx) in funnel" :key="stage.name" 
            class="h-12 rounded-xl bg-blue-600 transition-all hover:scale-[1.02]"
            :style="{ width: (100 - (idx * 15)) + '%', opacity: (1 - (idx * 0.15)) }"
          >
            <div class="h-full flex items-center justify-between px-6">
              <span class="text-[10px] font-black uppercase text-white tracking-widest">{{ stage.name }}</span>
              <span class="text-xs font-black text-white">{{ stage.value }}</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Lead Sources -->
      <div class="bg-white rounded-3xl border border-gray-100 p-8 shadow-sm h-96">
        <h3 class="text-sm font-black text-gray-900 uppercase tracking-widest mb-8">Lead Sources</h3>
        <div class="space-y-6">
          <div v-for="source in sources" :key="source.name" class="space-y-2">
            <div class="flex justify-between text-[10px] font-black uppercase tracking-widest">
              <span class="text-gray-400">{{ source.name }}</span>
              <span class="text-blue-600">{{ source.percentage }}%</span>
            </div>
            <div class="w-full h-2 bg-gray-50 rounded-full overflow-hidden">
              <div class="h-full bg-blue-600 rounded-full" :style="{ width: source.percentage + '%' }"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
const funnel = [
  { name: 'Leads', value: 1240 },
  { name: 'Enquiries', value: 840 },
  { name: 'Site Visits', value: 310 },
  { name: 'Negotiation', value: 120 },
  { name: 'Closed', value: 42 }
]

const sources = [
  { name: 'Google Ads', percentage: 45 },
  { name: 'Facebook Ads', percentage: 30 },
  { name: 'Direct/Website', percentage: 15 },
  { name: 'Referrals', percentage: 10 }
]
</script>
