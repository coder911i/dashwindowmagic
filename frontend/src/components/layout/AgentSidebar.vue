<template>
  <aside class="w-72 bg-[#0F1117] h-full flex flex-col border-r border-[#1E2433]">
    <!-- Logo Section -->
    <div class="p-8 flex items-center gap-3">
      <div class="w-10 h-10 bg-blue-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-500/10">
        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
        </svg>
      </div>
      <div>
        <h1 class="text-white font-bold tracking-tight uppercase text-lg leading-none">Watering</h1>
        <p class="text-emerald-500 text-[10px] font-bold tracking-widest uppercase">Agent Hub</p>
      </div>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 px-4 py-4 space-y-8 overflow-y-auto custom-scrollbar">
      <div v-for="group in menuGroups" :key="group.title">
        <p class="px-4 text-[10px] font-bold text-gray-500 uppercase tracking-[0.1em] mb-4 text-left">
          {{ group.title }}
        </p>
        <div class="space-y-1">
          <router-link 
            v-for="item in group.items" 
            :key="item.path"
            :to="item.path"
            v-slot="{ isActive }"
            class="flex items-center"
          >
            <div 
              class="w-full h-11 flex items-center px-4 rounded-lg transition-all group relative"
              :class="[isActive ? 'bg-[#1E2433] text-white shadow-lg shadow-black/20' : 'text-gray-400 hover:bg-[#1E2433] hover:text-white']"
            >
              <!-- Active Indicator Bar -->
              <div v-if="isActive" class="absolute left-0 w-1 h-5 bg-blue-600 rounded-r-full"></div>
              
              <component :is="item.icon" class="w-5 h-5 mr-3 transition-colors shrink-0" />
              <span class="font-bold text-sm tracking-tight">{{ item.name }}</span>
            </div>
          </router-link>
        </div>
      </div>
    </nav>

    <!-- Bottom Section: User Info -->
    <div class="p-4 border-t border-[#1E2433]">
      <div class="flex items-center gap-3 p-3 rounded-xl bg-[#1E2433]/50">
        <div class="w-10 h-10 rounded-full bg-emerald-500/20 flex items-center justify-center font-bold text-emerald-400 border border-emerald-500/30">
          {{ profile?.name?.charAt(0) || 'S' }}
        </div>
        <div class="flex-1 overflow-hidden text-left">
          <p class="text-xs font-bold text-white truncate">{{ profile?.name || 'Sales Agent' }}</p>
          <p class="text-[10px] text-gray-500 font-medium truncate">ID: {{ profile?.id?.slice(-8) || 'AGENT' }}</p>
        </div>
        <button @click="handleLogout" class="p-2 text-gray-500 hover:text-white hover:bg-white/5 rounded-lg transition-colors">
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
        </button>
      </div>
    </div>
  </aside>
</template>

<script setup>
import { useAuth } from '@/composables/useAuth'
import { useRouter } from 'vue-router'
import { 
  Squares2X2Icon as DashboardIcon, 
  ViewColumnsIcon as PipelineIcon,
  UserGroupIcon as LeadsIcon,
  CalendarDaysIcon as ScheduleIcon,
  DocumentMagnifyingGlassIcon as EnquiryIcon,
  CalculatorIcon as CalculatorIcon,
  SparklesIcon as FollowUpIcon
} from '@heroicons/vue/24/outline'

const { profile, logout } = useAuth()
const router = useRouter()

const handleLogout = async () => {
  await logout()
  router.push('/login')
}

const menuGroups = [
  {
    title: 'Core',
    items: [
      { name: 'Dashboard', path: '/agent/dashboard', icon: DashboardIcon },
      { name: 'Leads', path: '/agent/leads', icon: LeadsIcon },
      { name: 'Pipeline', path: '/agent/pipeline', icon: PipelineIcon },
    ]
  },
  {
    title: 'Tools',
    items: [
      { name: 'Enquiry Tracker', path: '/agent/enquiries', icon: EnquiryIcon },
      { name: 'EMI Calculator', path: '/agent/calculator', icon: CalculatorIcon },
      { name: 'Site Visits', path: '/agent/visits', icon: ScheduleIcon },
    ]
  },
  {
    title: 'AI Suite',
    items: [
      { name: 'AI Follow-up', path: '/agent/follow-up', icon: FollowUpIcon },
    ]
  }
]
</script>
