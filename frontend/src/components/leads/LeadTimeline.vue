<template>
  <div class="relative pl-8 space-y-12 before:absolute before:left-[11px] before:top-2 before:bottom-0 before:w-0.5 before:bg-gray-100">
    <div v-for="(event, idx) in timeline" :key="idx" class="relative group">
      <!-- Dot -->
      <div 
        class="absolute -left-[30px] top-1.5 w-5 h-5 rounded-full border-4 border-white shadow-sm z-10 transition-transform group-hover:scale-125"
        :class="event.action === 'ai_outreach' ? 'bg-[#7C3AED]' : 'bg-blue-600'"
      ></div>

      <div class="bg-white rounded-3xl border border-gray-100 p-6 shadow-sm hover:shadow-md transition-all">
        <div class="flex justify-between items-start mb-2">
          <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest">{{ formatDate(event.timestamp) }}</span>
          <span 
            class="text-[8px] font-black px-2 py-0.5 rounded-full uppercase tracking-tighter"
            :class="event.action === 'ai_outreach' ? 'bg-purple-50 text-[#7C3AED]' : 'bg-blue-50 text-blue-600'"
          >
            {{ event.action }}
          </span>
        </div>
        <p class="text-xs font-bold text-gray-800 leading-relaxed">{{ event.note }}</p>
        <div v-if="event.agentId" class="mt-4 flex items-center gap-2">
          <div class="w-4 h-4 rounded-full bg-gray-100 overflow-hidden">
            <div class="w-full h-full bg-gray-200"></div>
          </div>
          <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest">{{ event.agentName || 'Agent' }}</span>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
defineProps({
  timeline: { type: Array, required: true }
})

const formatDate = (date) => {
  if (!date) return ''
  const d = new Date(date?.seconds * 1000 || date)
  return d.toLocaleDateString('en-IN', { day: 'numeric', month: 'short', hour: '2-digit', minute: '2-digit' })
}
</script>
