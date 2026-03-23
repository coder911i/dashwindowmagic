<template>
  <div class="space-y-6">
    <div v-for="day in sortedDays" :key="day.date" class="relative pl-8 border-l border-gray-100 pb-8">
      <!-- Date Indicator -->
      <div class="absolute -left-[9px] top-0 w-4 h-4 rounded-full border-4 border-white bg-blue-600 shadow-sm"></div>
      
      <div class="mb-4">
        <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-widest">{{ formatDisplayDate(day.date) }}</h3>
        <p class="text-[8px] font-black text-blue-600 uppercase tracking-tighter">{{ day.visits.length }} VISITS BOOKED</p>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div 
          v-for="visit in day.visits" :key="visit.id"
          class="group p-5 bg-white border border-gray-100 rounded-2xl shadow-sm hover:shadow-xl hover:shadow-blue-500/5 hover:-translate-y-1 transition-all"
        >
          <div class="flex justify-between items-start mb-4">
            <div>
              <p class="text-xs font-black text-gray-900 tracking-tight">{{ visit.visitTime }}</p>
              <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">{{ visit.propertyName }}</p>
            </div>
            <span :class="['px-2 py-1 rounded-lg text-[8px] font-black uppercase tracking-widest', getStatusClass(visit.status)]">
              {{ visit.status }}
            </span>
          </div>

          <div class="flex items-center gap-3">
            <div class="w-8 h-8 rounded-full bg-blue-50 flex items-center justify-center text-[10px] font-black text-blue-600">
              {{ visit.leadName.charAt(0) }}
            </div>
            <div class="flex-1 min-w-0">
              <p class="text-[11px] font-black text-gray-900 truncate tracking-tight">{{ visit.leadName }}</p>
              <p class="text-[9px] font-bold text-gray-400 truncate tracking-tighter">{{ visit.notes || 'No specific requests' }}</p>
            </div>
          </div>

          <div class="mt-4 pt-4 border-t border-gray-50 flex gap-2 overflow-hidden items-center">
            <button @click="$emit('action', { type: 'complete', id: visit.id })" class="flex-1 py-2 bg-emerald-50 text-emerald-600 text-[8px] font-black uppercase tracking-widest rounded-lg hover:bg-emerald-100 transition-all">
              Mark Completed
            </button>
            <button @click="$emit('action', { type: 'cancel', id: visit.id })" class="px-3 py-2 text-[8px] font-black text-gray-400 uppercase tracking-widest hover:text-red-500 transition-all">
              Cancel
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'

const props = defineProps({
  visits: Array
})

const sortedDays = computed(() => {
  const groups = props.visits.reduce((acc, visit) => {
    const d = visit.visitDate
    if (!acc[d]) acc[d] = []
    acc[d].push(visit)
    return acc
  }, {})

  return Object.keys(groups).sort().map(date => ({
    date,
    visits: groups[date]
  }))
})

const formatDisplayDate = (d) => {
  return new Date(d).toLocaleDateString('en-IN', { weekday: 'long', day: '2-digit', month: 'short' })
}

const getStatusClass = (status) => {
  const map = {
    'scheduled': 'bg-blue-50 text-blue-600',
    'completed': 'bg-emerald-50 text-emerald-600',
    'cancelled': 'bg-red-50 text-red-600'
  }
  return map[status] || 'bg-gray-50 text-gray-400'
}
</script>
