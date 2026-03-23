<template>
  <div class="bg-[#0F1117] p-8 rounded-[40px] text-white overflow-hidden relative border border-white/5">
    <div class="absolute -right-8 -top-8 w-32 h-32 bg-blue-600/20 rounded-full blur-3xl"></div>
    
    <div class="relative z-10">
      <h3 class="text-xs font-black uppercase tracking-[0.2em] text-blue-400 mb-8">Schedule New Visit</h3>
      
      <div class="space-y-6">
        <div class="grid grid-cols-2 gap-6">
          <div class="space-y-3">
            <label class="text-[9px] font-black text-white/30 uppercase tracking-widest">Date</label>
            <input v-model="form.visitDate" type="date" class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl text-xs font-bold outline-none focus:border-blue-500 transition-all" />
          </div>
          <div class="space-y-3">
            <label class="text-[9px] font-black text-white/30 uppercase tracking-widest">Time Slot</label>
            <select v-model="form.visitTime" class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl text-xs font-bold outline-none focus:border-blue-500 transition-all appearance-none">
              <option v-for="t in times" :key="t" :value="t">{{ t }}</option>
            </select>
          </div>
        </div>

        <div class="space-y-3">
          <label class="text-[9px] font-black text-white/30 uppercase tracking-widest">Lead Name</label>
          <input v-model="form.leadName" type="text" placeholder="Start typing name..." class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl text-xs font-bold outline-none focus:border-blue-500 transition-all" />
        </div>

        <div class="space-y-3">
          <label class="text-[9px] font-black text-white/30 uppercase tracking-widest">Property Interested</label>
          <input v-model="form.propertyName" type="text" placeholder="Project Name" class="w-full px-4 py-3 bg-white/5 border border-white/10 rounded-xl text-xs font-bold outline-none focus:border-blue-500 transition-all" />
        </div>

        <button 
          @click="submit" :disabled="loading"
          class="w-full py-4 bg-blue-600 text-white text-[10px] font-black uppercase tracking-widest rounded-2xl hover:bg-blue-500 shadow-xl shadow-blue-500/20 transition-all disabled:opacity-50 mt-4"
        >
          {{ loading ? 'Booking...' : 'Confirm Schedule' }}
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { reactive, ref } from 'vue'

const props = defineProps({
  loading: Boolean
})

const emit = defineEmits(['submit'])

const times = ['10:00 AM', '11:00 AM', '12:00 PM', '02:00 PM', '03:00 PM', '04:00 PM', '05:00 PM']

const form = reactive({
  visitDate: new Date().toISOString().split('T')[0],
  visitTime: '11:00 AM',
  leadName: '',
  propertyName: '',
  leadId: 'demo-lead-id' // Simplified for demo
})

const submit = () => {
  if (!form.leadName || !form.propertyName) return
  emit('submit', { ...form })
  form.leadName = ''
  form.propertyName = ''
}
</script>
