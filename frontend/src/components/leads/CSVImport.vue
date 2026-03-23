<template>
  <Modal :show="show" title="Bulk Import Leads" @close="$emit('close')">
    <div class="space-y-8">
      <!-- Upload Area -->
      <div 
        @dragover.prevent="dragover = true" 
        @dragleave.prevent="dragover = false" 
        @drop.prevent="handleDrop"
        :class="['border-2 border-dashed rounded-[32px] p-12 text-center transition-all', 
          dragover ? 'border-blue-600 bg-blue-50/50 scale-[0.98]' : 'border-gray-100 bg-gray-50']"
      >
        <div class="w-16 h-16 bg-white rounded-2xl shadow-sm flex items-center justify-center mx-auto mb-6">
          <CloudArrowUpIcon class="w-8 h-8 text-blue-600" />
        </div>
        <p class="text-xs font-black text-gray-900 uppercase tracking-widest mb-2">Drop CSV File Here</p>
        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-[0.2em]">or click to browse your files</p>
        <input type="file" ref="fileInput" class="hidden" accept=".csv" @change="handleFileSelect" />
        <button @click="$refs.fileInput.click()" class="mt-8 text-[10px] font-black text-blue-600 uppercase tracking-widest hover:underline">Download CSV Template</button>
      </div>

      <div v-if="processing" class="flex items-center gap-4 p-6 bg-blue-50 rounded-2xl">
        <div class="w-5 h-5 border-2 border-blue-600 border-t-transparent rounded-full animate-spin"></div>
        <p class="text-xs font-black text-blue-600 uppercase tracking-widest">Processing {{ leads.length }} leads...</p>
      </div>

      <div v-if="leads.length > 0 && !processing" class="space-y-4">
        <h4 class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Preview (Showing first 5)</h4>
        <div class="bg-gray-50 rounded-2xl p-4 overflow-hidden">
          <table class="w-full text-left">
            <thead>
              <tr class="border-b border-gray-200">
                <th class="py-2 text-[9px] font-black text-gray-400 uppercase tracking-widest">Name</th>
                <th class="py-2 text-[9px] font-black text-gray-400 uppercase tracking-widest">Phone</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="lead in leads.slice(0, 5)" :key="lead.phone" class="border-b border-gray-100 last:border-0">
                <td class="py-2 text-[10px] font-bold text-gray-900">{{ lead.name }}</td>
                <td class="py-2 text-[10px] font-bold text-gray-400">{{ lead.phone }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <template #footer>
      <button @click="$emit('close')" class="px-6 py-3 text-[10px] font-black text-gray-400 uppercase tracking-widest hover:text-gray-900">Cancel</button>
      <button :disabled="leads.length === 0 || processing" @click="importLeads"
        class="bg-blue-600 text-white px-8 py-3 rounded-xl text-[10px] font-black uppercase tracking-widest shadow-xl disabled:opacity-50 hover:bg-blue-700 transition-all">
        Start Import
      </button>
    </template>
  </Modal>
</template>

<script setup>
import { ref } from 'vue'
import { CloudArrowUpIcon } from '@heroicons/vue/24/outline'
import Modal from '@/components/ui/Modal.vue'
import { useLeadsStore } from '@/stores/leads'

const props = defineProps({ show: Boolean })
const emit = defineEmits(['close', 'success'])
const store = useLeadsStore()

const leads = ref([])
const processing = ref(false)
const dragover = ref(false)

const handleDrop = (e) => {
  dragover.value = false
  const file = e.dataTransfer.files[0]
  if (file) parseCSV(file)
}

const handleFileSelect = (e) => {
  const file = e.target.files[0]
  if (file) parseCSV(file)
}

const parseCSV = (file) => {
  const reader = new FileReader()
  reader.onload = (e) => {
    const text = e.target.result
    const lines = text.split('\n').filter(l => l.trim())
    const results = []
    
    // Simple CSV parser (Assume headers: name, phone, budgetMin, budgetMax)
    const headers = lines[0].split(',').map(h => h.trim())
    for (let i = 1; i < lines.length; i++) {
        const values = lines[i].split(',').map(v => v.trim())
        const lead = {}
        headers.forEach((h, idx) => lead[h] = values[idx])
        if (lead.name && lead.phone) results.push(lead)
    }
    leads.value = results
  }
  reader.readAsText(file)
}

const importLeads = async () => {
  processing.value = true
  try {
    const count = await store.bulkImport(leads.value)
    emit('success', count)
    emit('close')
    leads.value = []
  } catch (err) {
    alert('Import failed.')
  } finally {
    processing.value = false
  }
}
</script>
