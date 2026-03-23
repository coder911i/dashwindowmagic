<template>
  <div class="flex flex-col gap-6 p-8">
    <!-- Lead Header -->
    <div v-if="lead" class="flex justify-between items-center p-6 bg-gray-50/50 border border-gray-100 rounded-2xl">
      <div class="flex items-center gap-5">
        <div class="w-14 h-14 rounded-2xl bg-[#0F1117] flex items-center justify-center font-black text-white text-xl shadow-lg">
          {{ lead.name.charAt(0) }}
        </div>
        <div>
          <h2 class="text-xl font-black text-gray-900 tracking-tight">{{ lead.name }}</h2>
          <div class="flex items-center gap-4 mt-1">
            <span class="text-[10px] font-black text-gray-400 uppercase tracking-widest">₹ {{ (lead.budgetMax / 100000).toFixed(1) }}L • {{ lead.configuration || '3 BHK' }}</span>
            <span class="text-[10px] font-black text-blue-600 uppercase tracking-widest">• {{ lead.location || 'Mumbai' }}</span>
          </div>
        </div>
      </div>
      <LeadScoreBadge :score="lead.score" />
    </div>

    <!-- Configuration -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
      <!-- Language -->
      <div class="space-y-4">
        <label class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Language</label>
        <div class="flex flex-wrap gap-2">
          <button 
            v-for="lang in languages" :key="lang.id"
            @click="form.language = lang.id"
            :class="['px-3 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest border transition-all', form.language === lang.id ? 'bg-blue-600 border-blue-600 text-white shadow-lg shadow-blue-500/20' : 'bg-white border-gray-100 text-gray-400 hover:border-blue-200']"
          >
            {{ lang.name }}
          </button>
        </div>
      </div>

      <!-- Message Type -->
      <div class="space-y-4">
        <label class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Purpose</label>
        <select v-model="form.messageType" class="w-full px-4 py-3 bg-white border border-gray-100 rounded-xl text-xs font-bold outline-none focus:ring-2 focus:ring-blue-500/10 transition-all appearance-none">
          <option value="initial">Initial Outreach</option>
          <option value="followup_2day">2-Day Follow-up</option>
          <option value="followup_7day">7-Day Re-engagement</option>
          <option value="visit_invite">Site Visit Invite</option>
          <option value="offer_nudge">Special Offer Nudge</option>
          <option value="price_drop">Price Drop Alert</option>
        </select>
      </div>

      <!-- Tone -->
      <div class="space-y-4">
        <label class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Tone</label>
        <div class="flex p-1 bg-gray-100 rounded-xl border border-gray-100">
          <button 
            @click="form.tone = 'formal'"
            :class="['flex-1 py-2 rounded-lg text-[10px] font-bold uppercase tracking-widest transition-all', form.tone === 'formal' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-400']"
          >
            Formal
          </button>
          <button 
            @click="form.tone = 'casual'"
            :class="['flex-1 py-2 rounded-lg text-[10px] font-bold uppercase tracking-widest transition-all', form.tone === 'casual' ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-400']"
          >
            Casual
          </button>
        </div>
      </div>
    </div>

    <!-- Generate Action -->
    <button 
      @click="handleGenerate" :disabled="!lead || loading"
      class="w-full py-4 bg-[#7C3AED] text-white font-black rounded-2xl transition-all shadow-xl shadow-purple-500/20 uppercase tracking-[0.2em] text-xs hover:translate-y-[-2px] hover:shadow-2xl disabled:opacity-50 flex items-center justify-center gap-3"
    >
      <SparklesIcon v-if="!loading" class="w-5 h-5 text-yellow-300" />
      <div v-else class="w-5 h-5 border-2 border-white/30 border-t-white rounded-full animate-spin"></div>
      Generate AI Outreach
    </button>

    <!-- Result Area -->
    <div v-if="result" class="space-y-4 animate-slide-up">
      <div class="relative">
        <textarea 
          v-model="result" 
          rows="6"
          class="w-full p-8 bg-white border border-gray-100 rounded-3xl text-sm font-medium leading-relaxed outline-none focus:ring-4 focus:ring-blue-500/5 transition-all shadow-sm"
        ></textarea>
        <div class="absolute bottom-6 right-8 text-[10px] font-black text-gray-300 uppercase tracking-widest">
          {{ result.length }} / 200 Characters
        </div>
      </div>

      <div class="flex gap-4">
        <button @click="copy(result)" class="flex-1 py-4 border border-gray-100 rounded-2xl text-[10px] font-black uppercase tracking-widest text-gray-500 hover:bg-gray-50 transition-all flex items-center justify-center gap-2">
          <DocumentDuplicateIcon class="w-4 h-4" /> Copy
        </button>
        <button class="flex-1 py-4 border border-gray-100 rounded-2xl text-[10px] font-black uppercase tracking-widest text-gray-500 hover:bg-gray-50 transition-all flex items-center justify-center gap-2">
          <CalendarDaysIcon class="w-4 h-4" /> Schedule
        </button>
        <button @click="sendWhatsApp" class="flex-[2] py-4 bg-[#25D366] text-white rounded-2xl text-[10px] font-black uppercase tracking-widest shadow-xl shadow-green-500/20 hover:bg-[#20bd5a] transition-all flex items-center justify-center gap-2">
          <img src="https://upload.wikimedia.org/wikipedia/commons/6/6b/WhatsApp.svg" class="w-4 h-4 invert" /> Send on WhatsApp
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive } from 'vue'
import { SparklesIcon, DocumentDuplicateIcon, CalendarDaysIcon } from '@heroicons/vue/24/outline'
import LeadScoreBadge from '@/components/ui/LeadScoreBadge.vue'

const props = defineProps({
  lead: Object,
  loading: Boolean
})

const emit = defineEmits(['generate'])

const languages = [
  { id: 'english', name: 'EN' },
  { id: 'hindi', name: 'HI' },
  { id: 'hinglish', name: 'HG' },
  { id: 'tamil', name: 'TA' },
  { id: 'telugu', name: 'TE' },
  { id: 'marathi', name: 'MR' },
  { id: 'kannada', name: 'KN' },
]

const form = reactive({
  language: 'hinglish',
  messageType: 'followup_2day',
  tone: 'casual'
})

const result = ref('')

const handleGenerate = async () => {
  const payload = {
    ...form,
    leadId: props.lead.id,
    leadName: props.lead.name,
    propertyName: props.lead.project || 'Property',
    budgetMin: props.lead.budgetMin,
    budgetMax: props.lead.budgetMax,
    bhk: props.lead.configuration || '3 BHK',
    locality: props.lead.location || 'Gurgaon'
  }
  
  const text = await emit('generate', payload)
  result.value = text
}

const copy = (txt) => {
  navigator.clipboard.writeText(txt)
  alert('Copied to clipboard!')
}

const sendWhatsApp = () => {
  const phone = props.lead.phone.replace(/[^0-9]/g, '')
  const text = encodeURIComponent(result.value)
  window.open(`https://wa.me/${phone}?text=${text}`, '_blank')
}
</script>

<style scoped>
@keyframes slide-up {
  from { opacity: 0; transform: translateY(20px); }
  to { opacity: 1; transform: translateY(0); }
}
.animate-slide-up {
  animation: slide-up 0.4s cubic-bezier(0.23, 1, 0.32, 1);
}
</style>
