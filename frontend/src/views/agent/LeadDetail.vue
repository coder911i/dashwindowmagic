<template>
  <div class="h-[calc(100vh-10rem)] flex flex-col lg:flex-row gap-8">
    <!-- Sidebar: Lead Info & Quick Actions -->
    <div class="lg:w-96 flex flex-col gap-6 shrink-0">
      <!-- Profile Card -->
      <div class="bg-white rounded-3xl border border-gray-100 p-8 shadow-sm">
        <div class="flex items-center gap-5 mb-8">
          <div class="w-16 h-16 rounded-2xl bg-[#0F1117] flex items-center justify-center font-black text-white text-2xl shadow-lg shadow-gray-200">
            {{ lead?.name?.charAt(0) || 'L' }}
          </div>
          <div>
            <h1 class="text-xl font-black text-gray-900 tracking-tight leading-tight">{{ lead?.name }}</h1>
            <p class="text-xs font-bold text-gray-400 mt-1 uppercase tracking-widest">Added via {{ lead?.source || 'Facebook Ads' }}</p>
          </div>
        </div>

        <div class="space-y-6">
          <div class="p-4 bg-gray-50 rounded-2xl border border-gray-100 flex items-center justify-between group cursor-pointer hover:bg-white hover:border-blue-100 transition-all">
            <div>
              <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1">Lead Score</p>
              <div class="flex items-center gap-2">
                <span class="text-xl font-black text-gray-900">{{ lead?.score || 'N/A' }}</span>
                <span :class="['px-2 py-0.5 rounded text-[8px] font-black uppercase text-white', lead?.score > 8 ? 'bg-red-600' : 'bg-amber-500']">
                  {{ lead?.score > 8 ? 'Hot' : 'Warm' }}
                </span>
              </div>
            </div>
            <button class="w-8 h-8 rounded-full border border-gray-200 flex items-center justify-center text-gray-400 group-hover:bg-blue-600 group-hover:text-white transition-all">
              <SparklesIcon class="w-4 h-4" />
            </button>
          </div>

          <div class="grid grid-cols-2 gap-4">
            <button class="bg-[#25D366] text-white py-3 rounded-xl font-extrabold text-[10px] uppercase tracking-widest flex items-center justify-center gap-2 shadow-lg shadow-green-500/10">
              <ChatBubbleOvalLeftEllipsisIcon class="w-4 h-4" /> WhatsApp
            </button>
            <button class="bg-[#0F1117] text-white py-3 rounded-xl font-extrabold text-[10px] uppercase tracking-widest flex items-center justify-center gap-2 shadow-lg shadow-gray-500/10">
              <PhoneIcon class="w-4 h-4" /> Call Agent
            </button>
          </div>
        </div>
      </div>

      <!-- Details List -->
      <div class="bg-white rounded-3xl border border-gray-100 p-8 shadow-sm">
        <h3 class="text-xs font-black text-gray-900 uppercase tracking-widest mb-6 border-b border-gray-50 pb-4">Lead Requirements</h3>
        <div class="space-y-4">
          <div v-for="(val, label) in details" :key="label" class="flex justify-between items-center group">
            <span class="text-[10px] font-bold text-gray-400 uppercase tracking-tighter">{{ label }}</span>
            <span class="text-xs font-bold text-gray-800">{{ val }}</span>
          </div>
        </div>
      </div>
    </div>

    <!-- Main Content: Timeline & AI Copilot -->
    <div class="flex-1 flex flex-col gap-6 overflow-hidden">
      <!-- Tabs -->
      <div class="flex gap-4 border-b border-gray-100 px-4">
        <button v-for="tab in ['Timeline', 'AI Copilot', 'Documents']" :key="tab" 
          :class="['pb-4 text-xs font-black uppercase tracking-widest transition-all px-2 border-b-2', currentTab === tab ? 'border-blue-600 text-blue-600' : 'border-transparent text-gray-400 hover:text-gray-600']"
          @click="currentTab = tab"
        >
          {{ tab }}
        </button>
      </div>

      <!-- Timeline View -->
      <div v-if="currentTab === 'Timeline'" class="flex-1 overflow-y-auto custom-scrollbar space-y-8 px-4 py-4">
        <div class="flex gap-4">
          <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center shrink-0 border-2 border-white shadow-sm">
            <label class="cursor-pointer"><PlusIcon class="w-5 h-5 text-blue-600" /></label>
          </div>
          <div class="flex-1">
            <textarea placeholder="Add communication note..." class="w-full bg-white border border-gray-200 rounded-2xl p-4 text-sm outline-none focus:ring-2 focus:ring-blue-500/10 transition-all min-h-[100px]"></textarea>
            <div class="flex justify-between items-center mt-3">
              <div class="flex gap-2">
                <button class="bg-gray-100 p-2 rounded-lg text-gray-500"><PaperClipIcon class="w-4 h-4" /></button>
                <button class="bg-gray-100 p-2 rounded-lg text-gray-500"><CalendarIcon class="w-4 h-4" /></button>
              </div>
              <button class="bg-blue-600 text-white px-6 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest shadow-lg shadow-blue-500/20">Save Activity</button>
            </div>
          </div>
        </div>

        <div class="space-y-12 pl-5 border-l-2 border-gray-50 ml-5">
          <div v-for="event in lead?.timeline || []" :key="event.id" class="relative group">
            <!-- Timeline Marker -->
            <div class="absolute -left-[30px] top-0 w-4 h-4 rounded-full bg-white border-4 border-blue-600 shadow-sm z-10"></div>
            
            <div class="flex flex-col gap-2">
              <div class="flex items-center gap-3">
                <span class="text-[10px] font-black uppercase text-blue-600 tracking-tighter">{{ event.action }}</span>
                <span class="text-[9px] font-bold text-gray-400 uppercase tracking-widest">{{ formatDate(event.timestamp) }}</span>
              </div>
              <p class="text-sm font-medium text-gray-700 leading-relaxed bg-white p-5 rounded-2xl border border-gray-50 shadow-sm">{{ event.note }}</p>
              <div class="flex items-center gap-2 mt-1 px-1">
                <div class="w-5 h-5 rounded-full bg-gray-200 text-[8px] flex items-center justify-center font-bold">AK</div>
                <span class="text-[10px] font-bold text-gray-500">Recorded by Agent Akash</span>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- AI Copilot Tab -->
      <div v-if="currentTab === 'AI Copilot'" class="flex-1 flex flex-col items-center justify-center text-center p-12 bg-blue-50/30 rounded-3xl border border-dashed border-blue-200">
        <div class="w-16 h-16 bg-blue-600 rounded-2xl flex items-center justify-center mb-6 shadow-xl shadow-blue-500/20">
          <SparklesIcon class="w-10 h-10 text-white animate-pulse" />
        </div>
        <h2 class="text-2xl font-black text-gray-900 mb-2 uppercase tracking-tighter">AI Assistant Active</h2>
        <p class="text-gray-500 max-w-sm text-sm font-medium leading-relaxed">I'm analyzing lead behavior and market trends. I'll provide outreach suggestions soon.</p>
        <button class="mt-8 bg-[#0F1117] text-white px-8 py-4 rounded-2xl text-xs font-black uppercase tracking-[0.2em] shadow-xl hover:translate-y-[-2px] transition-all">Generate Outreach Message</button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, computed } from 'vue'
import { useRoute } from 'vue-router'
import { useLeadsStore } from '@/stores/leads'
import { 
  PlusIcon, 
  PaperClipIcon, 
  CalendarIcon, 
  PhoneIcon, 
  ChatBubbleOvalLeftEllipsisIcon,
  SparklesIcon
} from '@heroicons/vue/24/outline'

const route = useRoute()
const leadsStore = useLeadsStore()
const currentTab = ref('Timeline')
const lead = ref(null)

const details = computed(() => {
  if (!lead.value) return {}
  return {
    'Budget Range': `₹ ${lead.value.budgetMin / 100000}L - ${lead.value.budgetMax / 100000}L`,
    'Location': lead.value.location || 'Gurgaon',
    'Config': lead.value.configuration || '3 BHK',
    'Phone': lead.value.phone,
    'Project': lead.value.project || 'Emerald Hills',
  }
})

onMounted(async () => {
  const leadId = route.params.id
  lead.value = await leadsStore.fetchLeadById(leadId)
})

const formatDate = (ts) => {
  if (!ts) return ''
  const date = ts?.toDate ? ts.toDate() : new Date(ts)
  return date.toLocaleString('en-IN', {
    day: 'numeric',
    month: 'short',
    hour: '2-digit',
    minute: '2-digit'
  })
}
</script>
