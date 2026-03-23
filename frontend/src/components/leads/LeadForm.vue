<template>
  <Modal :show="show" :title="lead ? 'Edit Lead' : 'Add New Lead'" @close="$emit('close')">
    <form @submit.prevent="handleSubmit" class="space-y-8 text-left">
      <div class="grid grid-cols-2 gap-6">
        <div class="space-y-2">
          <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest pl-2">Lead Name</label>
          <input v-model="form.name" required type="text" placeholder="Rahul Sharma" 
            class="w-full px-6 py-4 bg-gray-50 rounded-2xl border-none text-xs font-bold text-gray-900 focus:ring-2 focus:ring-blue-600 outline-none transition-all" />
        </div>
        <div class="space-y-2">
          <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest pl-2">Phone Number</label>
          <input v-model="form.phone" required type="tel" placeholder="+91 98765 43210" 
            class="w-full px-6 py-4 bg-gray-50 rounded-2xl border-none text-xs font-bold text-gray-900 focus:ring-2 focus:ring-blue-600 outline-none transition-all" />
        </div>
      </div>

      <div class="grid grid-cols-2 gap-6">
        <div class="space-y-2">
          <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest pl-2">Min Budget (₹)</label>
          <input v-model.number="form.budgetMin" required type="number" 
            class="w-full px-6 py-4 bg-gray-50 rounded-2xl border-none text-xs font-bold text-gray-900 focus:ring-2 focus:ring-blue-600 outline-none transition-all" />
        </div>
        <div class="space-y-2">
          <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest pl-2">Max Budget (₹)</label>
          <input v-model.number="form.budgetMax" required type="number" 
            class="w-full px-6 py-4 bg-gray-50 rounded-2xl border-none text-xs font-bold text-gray-900 focus:ring-2 focus:ring-blue-600 outline-none transition-all" />
        </div>
      </div>

      <div class="grid grid-cols-2 gap-6">
        <div class="space-y-2">
          <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest pl-2">Configuration</label>
          <select v-model="form.bhk" class="w-full px-6 py-4 bg-gray-50 rounded-2xl border-none text-xs font-bold text-gray-900 focus:ring-2 focus:ring-blue-600 outline-none transition-all">
            <option value="1">1 BHK</option>
            <option value="2">2 BHK</option>
            <option value="3">3 BHK</option>
            <option value="4">4+ BHK</option>
          </select>
        </div>
        <div class="space-y-2">
          <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest pl-2">Preferred Locality</label>
          <input v-model="form.locality" type="text" placeholder="Sector 62, Noida" 
            class="w-full px-6 py-4 bg-gray-50 rounded-2xl border-none text-xs font-bold text-gray-900 focus:ring-2 focus:ring-blue-600 outline-none transition-all" />
        </div>
      </div>

      <div class="space-y-6 pt-4">
        <button type="submit" :disabled="loading"
          class="w-full bg-[#0F1117] text-white py-5 rounded-[24px] text-xs font-black uppercase tracking-widest shadow-2xl hover:translate-y-[-2px] transition-all disabled:opacity-50">
          {{ loading ? 'Saving...' : (lead ? 'Update Lead Profile' : 'Create New Lead') }}
        </button>
      </div>
    </form>
  </Modal>
</template>

<script setup>
import { ref, watch } from 'vue'
import Modal from '@/components/ui/Modal.vue'
import { useLeadsStore } from '@/stores/leads'

const props = defineProps({
  show: Boolean,
  lead: Object
})

const emit = defineEmits(['close'])
const store = useLeadsStore()
const loading = ref(false)

const form = ref({
  name: '',
  phone: '',
  budgetMin: 5000000,
  budgetMax: 15000000,
  bhk: '2',
  locality: '',
  isNRI: false
})

watch(() => props.lead, (newLead) => {
  if (newLead) {
    form.value = { ...newLead }
  } else {
    form.value = {
      name: '', phone: '', budgetMin: 5000000, budgetMax: 15000000, bhk: '2', locality: '', isNRI: false
    }
  }
}, { immediate: true })

const handleSubmit = async () => {
  loading.value = true
  try {
    if (props.lead) {
      await store.updateLead(props.lead.id, form.value)
    } else {
      await store.addLead(form.value)
    }
    emit('close')
  } catch (err) {
    alert('Failed to save lead.')
  } finally {
    loading.value = false
  }
}
</script>
