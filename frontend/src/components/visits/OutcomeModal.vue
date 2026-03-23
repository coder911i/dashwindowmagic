<template>
  <Modal :show="show" title="Update Visit Outcome" @close="$emit('close')">
    <div class="space-y-10">
      <div class="text-center">
        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">How was the visit with</p>
        <h2 class="text-2xl font-black text-gray-900 uppercase tracking-tight">{{ visit?.leadName }}</h2>
      </div>

      <div class="grid grid-cols-2 gap-4">
        <button v-for="status in outcomes" :key="status.value"
          @click="selectedOutcome = status.value"
          :class="['p-6 rounded-3xl border text-center transition-all', 
            selectedOutcome === status.value ? 'border-blue-600 bg-blue-50/50 shadow-inner' : 'border-gray-50 bg-gray-50/50 hover:bg-gray-50']"
        >
          <p class="text-[10px] font-black uppercase tracking-widest text-gray-900">{{ status.label }}</p>
        </button>
      </div>

      <div class="space-y-2">
        <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest pl-2">Follow up Notes</label>
        <textarea v-model="notes" rows="3" placeholder="Client loved the balcony view, requested floor plans..."
          class="w-full px-6 py-4 bg-gray-50 rounded-2xl border-none text-xs font-bold text-gray-900 focus:ring-2 focus:ring-blue-600 outline-none transition-all"></textarea>
      </div>

      <div v-if="selectedOutcome" class="p-6 bg-purple-50 rounded-3xl border border-purple-100">
        <div class="flex items-center gap-2 mb-2">
          <SparklesIcon class="w-4 h-4 text-purple-600" />
          <p class="text-[10px] font-black text-purple-600 uppercase tracking-widest">AI Next Step Recommendation</p>
        </div>
        <p class="text-xs font-bold text-gray-800 leading-relaxed italic">
          "Send the 3BHK brochure and 2% early-bird discount offer. Lead show high intent on floor 12."
        </p>
      </div>

      <button @click="save" :disabled="!selectedOutcome"
        class="w-full bg-[#0F1117] text-white py-5 rounded-[24px] text-xs font-black uppercase tracking-widest shadow-2xl hover:translate-y-[-2px] transition-all disabled:opacity-50">
        Log Outcome & Complete
      </button>
    </div>
  </Modal>
</template>

<script setup>
import { ref } from 'vue'
import { SparklesIcon } from '@heroicons/vue/24/outline'
import Modal from '@/components/ui/Modal.vue'

const props = defineProps({ show: Boolean, visit: Object })
const emit = defineEmits(['close', 'save'])

const outcomes = [
  { label: 'Interested', value: 'interested' },
  { label: 'Negotiating', value: 'negotiating' },
  { label: 'Follow-up', value: 'follow_up' },
  { label: 'Not Interested', value: 'not_interested' }
]

const selectedOutcome = ref('')
const notes = ref('')

const save = () => {
  emit('save', { outcome: selectedOutcome.value, notes: notes.value })
  emit('close')
}
</script>
