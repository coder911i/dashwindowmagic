<template>
  <div class="bg-white p-8 rounded-[32px] border border-gray-100 shadow-sm relative overflow-hidden">
    <div class="absolute -right-8 -top-8 w-32 h-32 bg-emerald-50 rounded-full blur-3xl opacity-50"></div>
    
    <div class="relative z-10">
      <h2 class="text-xs font-black text-gray-400 uppercase tracking-[0.2em] mb-8">Compliance Check</h2>
      
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="space-y-4">
          <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Select State</label>
          <select v-model="form.state" class="w-full px-4 py-4 bg-gray-50 border border-gray-100 rounded-2xl text-xs font-bold outline-none appearance-none focus:ring-4 focus:ring-blue-500/5 transition-all">
            <option v-for="s in states" :key="s" :value="s">{{ s }}</option>
          </select>
        </div>

        <div class="space-y-4">
          <label class="text-[10px] font-black text-gray-400 uppercase tracking-widest">RERA Registration No.</label>
          <div class="relative">
            <input 
              v-model="form.reraNumber" 
              type="text" 
              placeholder="e.g. P518000..."
              class="w-full px-4 py-4 bg-gray-50 border border-gray-100 rounded-2xl text-xs font-bold outline-none focus:ring-4 focus:ring-blue-500/5 transition-all"
            />
            <button 
              @click="handleValidate" :disabled="loading"
              class="absolute right-2 top-2 bottom-2 px-6 bg-[#0F1117] text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-gray-800 transition-all disabled:opacity-50"
            >
              Validate
            </button>
          </div>
        </div>
      </div>

      <!-- Result Banner -->
      <Transition name="fade">
        <div v-if="result" :class="['mt-8 p-6 rounded-2xl border flex items-center justify-between', result.isValid ? 'bg-emerald-50 border-emerald-100' : 'bg-red-50 border-red-100']">
          <div class="flex items-center gap-4">
            <div :class="['p-2 rounded-xl', result.isValid ? 'bg-emerald-500/10' : 'bg-red-500/10']">
              <CheckBadgeIcon v-if="result.isValid" class="w-6 h-6 text-emerald-600" />
              <ExclamationTriangleIcon v-else class="w-6 h-6 text-red-600" />
            </div>
            <div>
              <p :class="['text-xs font-black uppercase tracking-widest', result.isValid ? 'text-emerald-900' : 'text-red-900']">
                {{ result.message }}
              </p>
              <p v-if="result.hint && !result.isValid" class="text-[10px] font-bold text-red-600/60 uppercase mt-1">
                Format hint: {{ result.hint }}
              </p>
            </div>
          </div>
          <button v-if="result.isValid" @click="$emit('save', form)" class="px-4 py-2 bg-white border border-emerald-200 text-emerald-600 text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-emerald-100 transition-all">
            Log Action
          </button>
        </div>
      </Transition>
    </div>
  </div>
</template>

<script setup>
import { reactive, ref } from 'vue'
import { CheckBadgeIcon, ExclamationTriangleIcon } from '@heroicons/vue/24/solid'
import { useReraStore } from '@/stores/rera'

const reraStore = useReraStore()
const emit = defineEmits(['save'])

const states = ['Maharashtra', 'Karnataka', 'Uttar Pradesh', 'Haryana', 'Rajasthan', 'Gujarat']
const form = reactive({ state: 'Maharashtra', reraNumber: '' })
const result = ref(null)
const loading = ref(false)

const handleValidate = async () => {
  if (!form.reraNumber) return
  loading.value = true
  try {
    result.value = await reraStore.validateFormat(form.state, form.reraNumber)
  } finally {
    loading.value = false
  }
}
</script>

<style scoped>
.fade-enter-active, .fade-leave-active { transition: opacity 0.3s; }
.fade-enter-from, .fade-leave-to { opacity: 0; }
</style>
