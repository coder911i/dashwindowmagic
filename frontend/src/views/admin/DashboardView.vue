<template>
  <div class="space-y-8">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
      <div>
        <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">Admin Overview</h1>
        <p class="text-gray-500 text-sm font-medium">Real-time performance metrics for your agency.</p>
      </div>
      <div class="flex items-center gap-3">
        <select class="bg-white border border-gray-200 text-xs font-bold rounded-lg px-3 py-2 outline-none focus:ring-2 focus:ring-blue-500/10 transition-all">
          <option>Last 7 Days</option>
          <option>Last 30 Days</option>
          <option>This Month</option>
        </select>
        <button class="bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold px-4 py-2 rounded-lg transition-all shadow-lg shadow-blue-500/20">
          Download Report
        </button>
      </div>
    </div>

    <!-- KPI Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
      <KpiCard label="Total Revenue" value="₹ 12.4L" :trend="12" suffix="Closed">
        <template #icon>
          <CurrencyRupeeIcon class="w-5 h-5 text-blue-600" />
        </template>
      </KpiCard>
      
      <KpiCard label="New Leads" value="148" :trend="8" suffix="This week">
        <template #icon>
          <UserIcon class="w-5 h-5 text-blue-600" />
        </template>
      </KpiCard>

      <KpiCard label="Conversion Rate" value="4.2" suffix="%" :trend="-2">
        <template #icon>
          <ArrowTrendingUpIcon class="w-5 h-5 text-blue-600" />
        </template>
      </KpiCard>

      <KpiCard label="Active Agents" value="12" suffix="Online">
        <template #icon>
          <UserGroupIcon class="w-5 h-5 text-blue-600" />
        </template>
      </KpiCard>
    </div>

    <!-- Charts & Tables Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
      <!-- Recent Lead Activity -->
      <div class="lg:col-span-2 bg-white rounded-2xl border border-gray-100 p-6 shadow-sm overflow-hidden">
        <div class="flex items-center justify-between mb-6">
          <h2 class="text-lg font-bold text-gray-900">Recent Agency Performance</h2>
          <router-link to="/admin/leads" class="text-xs font-bold text-blue-600 hover:underline tracking-tight">View All Leads</router-link>
        </div>
        
        <div class="space-y-4">
          <div v-for="i in 5" :key="i" class="flex items-center justify-between p-4 rounded-xl border border-gray-50 bg-gray-50/30 group hover:border-blue-100 hover:bg-white transition-all">
            <div class="flex items-center gap-4">
              <div class="w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center font-bold text-blue-600">
                P
              </div>
              <div>
                <p class="text-sm font-bold text-gray-900">Prateek Goyal</p>
                <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest">Goregaon West • ₹ 1.2 Cr</p>
              </div>
            </div>
            <div class="text-right">
              <Badge type="success">Booking Done</Badge>
              <p class="text-[10px] text-gray-400 font-medium mt-1">2h ago by Agent Akash</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Agent Leaderboard -->
      <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm">
        <h2 class="text-lg font-bold text-gray-900 mb-6">Top Performers</h2>
        <div class="space-y-6">
          <div v-for="(agent, idx) in leaderboard" :key="agent.name" class="flex items-center gap-4">
            <span class="text-xs font-black text-gray-300 w-4">{{ idx + 1 }}</span>
            <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center font-bold text-blue-600">
              {{ agent.name.charAt(0) }}
            </div>
            <div class="flex-1">
              <p class="text-sm font-bold text-gray-900 leading-none mb-1">{{ agent.name }}</p>
              <div class="w-full h-1.5 bg-gray-100 rounded-full overflow-hidden">
                <div class="h-full bg-blue-600 rounded-full" :style="{ width: agent.score + '%' }"></div>
              </div>
            </div>
            <span class="text-xs font-extrabold text-blue-600">{{ agent.deals }} Deals</span>
          </div>
        </div>
        <button class="w-full mt-8 py-3 border-2 border-dashed border-gray-200 rounded-xl text-xs font-bold text-gray-400 hover:bg-gray-50 transition-all uppercase">
          Full Agent Report
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { 
  CurrencyRupeeIcon, 
  UserIcon, 
  ArrowTrendingUpIcon, 
  UserGroupIcon 
} from '@heroicons/vue/24/outline'
import KpiCard from '@/components/ui/KpiCard.vue'
import Badge from '@/components/ui/Badge.vue'

const leaderboard = [
  { name: 'Amit Singh', deals: 14, score: 92 },
  { name: 'Priya Verma', deals: 11, score: 85 },
  { name: 'Sanjay Jain', deals: 9, score: 75 },
  { name: 'Neha Gupta', deals: 7, score: 62 },
  { name: 'Rajesh Kumar', deals: 5, score: 45 },
]
</script>
