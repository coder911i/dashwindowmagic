<template>
  <div class="space-y-8">
    <!-- Agent Profile Header -->
    <div class="bg-[#0F1117] rounded-3xl p-8 text-white relative overflow-hidden">
      <!-- Pattern Overlay -->
      <div class="absolute inset-0 opacity-10 bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')]"></div>
      
      <div class="relative z-10 flex flex-col md:flex-row justify-between gap-6">
        <div>
          <h1 class="text-3xl font-extrabold tracking-tight mb-2 uppercase">Good Morning, {{ profile?.name?.split(' ')[0] || 'Warrior' }}!</h1>
          <p class="text-blue-400 font-bold tracking-[0.2em] text-[10px] uppercase">
            Let's crush your targets for {{ currentMonth }}
          </p>
        </div>
        <div class="flex items-center gap-8">
          <div class="text-center">
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Monthly Target</p>
            <p class="text-xl font-black">₹ 15.0L</p>
          </div>
          <div class="h-10 w-px bg-white/10"></div>
          <div class="text-center">
            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Achieved</p>
            <p class="text-xl font-black text-blue-500">₹ 8.5L</p>
          </div>
        </div>
      </div>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
      <div v-for="stat in agentStats" :key="stat.label" class="bg-white p-6 rounded-2xl border border-gray-100 shadow-sm relative group overflow-hidden">
        <div class="absolute top-0 right-0 w-16 h-16 opacity-5 transform translate-x-4 -translate-y-4">
          <component :is="stat.icon" class="w-full h-full text-blue-600" />
        </div>
        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">{{ stat.label }}</p>
        <div class="flex items-baseline gap-2">
          <h3 class="text-3xl font-black text-gray-900">{{ stat.value }}</h3>
          <span class="text-xs font-bold text-emerald-600">{{ stat.trend }}</span>
        </div>
      </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
      <!-- Hot Leads Section -->
      <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm">
        <div class="flex items-center justify-between mb-6">
          <h2 class="text-lg font-extrabold text-gray-900 uppercase tracking-tight">Today's Hot Leads</h2>
          <router-link to="/agent/pipeline" class="text-[10px] font-black text-blue-600 hover:underline uppercase tracking-widest">Go to Pipeline</router-link>
        </div>
        <div class="space-y-4">
          <div v-for="lead in hotLeads" :key="lead.name" class="flex items-center justify-between p-4 rounded-xl bg-red-50/30 border border-red-100/50 group hover:border-red-200 transition-all cursor-pointer">
            <div class="flex items-center gap-4">
              <div class="w-10 h-10 rounded-xl bg-red-600 flex items-center justify-center font-bold text-white shadow-lg shadow-red-600/10">
                {{ lead.name.charAt(0) }}
              </div>
              <div>
                <p class="text-sm font-bold text-gray-900 leading-tight">
                  {{ lead.name }}
                  <span class="ml-2 inline-flex items-center px-1.5 py-0.5 rounded-full bg-red-600 text-[8px] text-white">🔥 9.2</span>
                </p>
                <p class="text-[10px] text-gray-500 font-bold uppercase tracking-tight mt-0.5">{{ lead.project }} • ₹ {{ lead.budget }}</p>
              </div>
            </div>
            <button class="text-white bg-[#0F1117] px-3 py-1.5 rounded-lg text-[10px] font-bold uppercase tracking-widest hover:bg-blue-600 transition-all shadow-lg">
              Call Now
            </button>
          </div>
        </div>
      </div>

      <!-- Action Required / Notifications -->
      <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm">
        <h2 class="text-lg font-extrabold text-gray-900 uppercase tracking-tight mb-6">Action Items</h2>
        <div class="space-y-4">
          <div v-for="action in actions" :key="action.title" class="flex gap-4 p-4 rounded-xl border border-gray-100 hover:border-blue-100 group transition-all">
            <div :class="['w-10 h-10 shrink-0 rounded-full flex items-center justify-center font-bold border-2', action.color]">
              {{ action.icon }}
            </div>
            <div class="flex-1">
              <div class="flex justify-between">
                <p class="text-sm font-bold text-gray-800">{{ action.title }}</p>
                <span class="text-[10px] font-bold text-gray-400 uppercase">{{ action.time }}</span>
              </div>
              <p class="text-xs text-gray-500 mt-1">{{ action.desc }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'
import { useAuth } from '@/composables/useAuth'
import { 
  PhoneIcon, 
  CalendarIcon, 
  MapPinIcon, 
  StarIcon 
} from '@heroicons/vue/24/outline'

const { profile } = useAuth()

const currentMonth = computed(() => {
  return new Date().toLocaleString('default', { month: 'long' })
})

const agentStats = [
  { label: 'Active Pipeline', value: '42', trend: '+12%', icon: PhoneIcon },
  { label: 'Site Visits', value: '18', trend: '+5%', icon: MapPinIcon },
  { label: 'Follow Ups', value: '156', trend: '-2%', icon: CalendarIcon },
  { label: 'Lead Score', value: '8.4', trend: '↑', icon: StarIcon },
]

const hotLeads = [
  { name: 'Karan Mehra', project: 'Prestige Falcon', budget: '2.4 Cr', score: 9.2 },
  { name: 'Dr. Anjali Rao', project: 'Godrej Woods', budget: '1.8 Cr', score: 8.8 },
  { name: 'Sameer Sheikh', project: 'Lodha Park', budget: '5.5 Cr', score: 8.5 },
]

const actions = [
  { title: 'Callback Requested', desc: 'Customer interested in 3BHK show-flat', time: '10:30 AM', icon: '📞', color: 'border-blue-500 text-blue-500 bg-blue-50' },
  { title: 'Site Visit Confirmed', desc: 'Mrs. Khanna visiting Emerald Bay at 4PM', time: 'IPHONE NOTIF', icon: '📍', color: 'border-emerald-500 text-emerald-500 bg-emerald-50' },
  { title: 'Payment Reminder', desc: 'Arun Bhatia - Token payment due today', time: 'URGENT', icon: '💰', color: 'border-red-500 text-red-500 bg-red-50' },
]
</script>
