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

      <!-- NRI Profile (Conditional) -->
      <NRIPanel v-if="lead?.isNRI" :lead="lead" />

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
      <div v-if="currentTab === 'AI Copilot'" class="flex-1 flex flex-col gap-8 overflow-y-auto custom-scrollbar px-4 py-4">
        <!-- Outreach Generator -->
        <div class="bg-gradient-to-br from-blue-600 to-[#7C3AED] rounded-[40px] p-10 text-white shadow-2xl relative overflow-hidden group">
          <div class="absolute -right-20 -top-20 w-80 h-80 bg-white/10 rounded-full blur-3xl group-hover:bg-white/20 transition-all duration-700"></div>
          
          <div class="relative z-10">
            <div class="flex items-center gap-3 mb-6">
              <div class="w-10 h-10 bg-white/20 backdrop-blur rounded-xl flex items-center justify-center">
                <SparklesIcon class="w-6 h-6 text-white" />
              </div>
              <h3 class="text-base font-black uppercase tracking-widest">AI Outreach Engine</h3>
            </div>
            
            <p v-if="!generatedMessage" class="text-blue-50 text-sm font-medium leading-relaxed max-w-md mb-8">
              Based on {{ lead?.name }}'s interest in {{ lead?.locality }}, I can draft a high-conversion follow-up message.
            </p>
            
            <div v-if="generatedMessage" class="bg-white/10 backdrop-blur-md rounded-3xl p-6 mb-8 border border-white/20">
              <p class="text-xs font-bold leading-relaxed whitespace-pre-line">{{ generatedMessage }}</p>
            </div>

            <button 
              @click="generateMessage" 
              :disabled="generating"
              class="bg-white text-blue-600 px-8 py-4 rounded-2xl text-[10px] font-black uppercase tracking-[0.2em] shadow-xl hover:translate-y-[-2px] active:translate-y-0 transition-all disabled:opacity-50"
            >
              {{ generating ? 'Analyzing Lead Intent...' : (generatedMessage ? 'Regenerate Draft' : 'Generate Outreach Concept') }}
            </button>
          </div>
        </div>

        <!-- Property Matches -->
        <div>
          <div class="flex justify-between items-center mb-8 px-4">
            <h3 class="text-xs font-black text-gray-900 uppercase tracking-widest">Recommended Inventory</h3>
            <span class="text-[9px] font-black text-blue-600 uppercase">Matched by AI</span>
          </div>

          <div v-if="matchingProperties.length === 0" class="p-12 text-center bg-gray-50 rounded-[32px] border border-gray-100 italic">
            <p class="text-xs font-bold text-gray-400">Searching your inventory for matches...</p>
          </div>

          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div v-for="prop in matchingProperties" :key="prop.id" 
              class="bg-white rounded-[32px] border border-gray-100 p-6 shadow-sm hover:shadow-xl transition-all group flex flex-col text-left">
              <div class="flex justify-between items-center mb-4">
                <span class="px-3 py-1 bg-emerald-50 text-emerald-600 text-[8px] font-black rounded-full uppercase">{{ prop.matchScore }}% Match</span>
                <button class="text-gray-300 hover:text-blue-600 transition-colors"><PaperAirplaneIcon class="w-4 h-4" /></button>
              </div>
              <h4 class="text-sm font-black text-gray-900 uppercase tracking-tighter mb-1">{{ prop.title }}</h4>
              <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">{{ prop.locality }} • ₹{{ prop.price / 100000 }}L</p>
              
              <div class="mt-6 pt-4 border-t border-gray-50 flex justify-between items-center">
                <span class="text-[9px] font-black text-blue-600 uppercase">{{ prop.bhk }} BHK</span>
                <button class="text-[9px] font-black text-gray-900 uppercase tracking-widest underline underline-offset-4">View Unit</button>
              </div>
            </div>
          </div>
        </div>
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
  SparklesIcon,
  PaperAirplaneIcon
} from '@heroicons/vue/24/outline'
import NRIPanel from '@/components/leads/NRIPanel.vue'

const route = useRoute()
const leadsStore = useLeadsStore()
const currentTab = ref('Timeline')
const lead = ref(null)

// AI Copilot Logic
const generating = ref(false)
const generatedMessage = ref('')
const matchingProperties = ref([])

const generateMessage = async () => {
    generating.value = true
    // Simulation of AI behavior
    setTimeout(() => {
        generatedMessage.value = `Hey ${lead.value?.name}! 👋\n\nHope you're having a great day. I remember you were looking for a ${lead.value?.configuration || '3 BHK'} in ${lead.value?.location || 'Gurgaon'} within the ₹${lead.value?.budgetMin / 100000}L range.\n\nI've found a new unit at ${matchingProperties.value[0]?.title || 'Emerald Hills'} that fits your criteria perfectly. Would you like me to send over the floor plans and brochure?`
        generating.value = false
    }, 1500)
}

const findMatches = () => {
    // Simulated matching logic
    matchingProperties.value = [
        { id: 1, title: 'Godrej Air', locality: 'Sector 85, Gurgaon', price: 14500000, bhk: '3', matchScore: 98 },
        { id: 2, title: 'M3M Heights', locality: 'Sector 65, Gurgaon', price: 18000000, bhk: '3', matchScore: 85 }
    ]
}

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
  findMatches()
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
