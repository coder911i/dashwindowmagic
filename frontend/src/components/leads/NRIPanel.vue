<template>
  <div class="bg-blue-900 rounded-[40px] p-8 text-white text-left relative overflow-hidden shadow-2xl">
    <!-- Decorative background -->
    <div class="absolute -right-10 -top-10 w-40 h-40 bg-white/5 rounded-full blur-3xl"></div>
    
    <div class="flex justify-between items-start mb-8 relative z-10">
      <div>
        <h3 class="text-xs font-black uppercase tracking-[0.2em] text-blue-300 mb-2 italic">NRI Buyer Profile</h3>
        <p class="text-2xl font-black tracking-tighter">{{ lead.nri?.country || 'International Residence' }}</p>
      </div>
      <GlobeEuropeAfricaIcon class="w-10 h-10 text-blue-400 opacity-50" />
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 relative z-10">
      <!-- Timezone -->
      <div class="space-y-4">
        <div class="p-5 bg-white/5 rounded-3xl border border-white/10">
          <p class="text-[9px] font-black uppercase tracking-widest text-blue-300 mb-2">Local Time</p>
          <div class="flex items-baseline gap-2">
            <span class="text-xl font-black">{{ localTime }}</span>
            <span class="text-[10px] font-bold text-blue-400 uppercase tracking-widest">({{ lead.nri?.timezone }})</span>
          </div>
          <p class="text-[9px] font-bold text-emerald-400 uppercase mt-2">● Best time to call</p>
        </div>
      </div>

      <!-- Currency Conversion -->
      <div class="space-y-4">
        <div class="p-5 bg-white/5 rounded-3xl border border-white/10">
          <p class="text-[9px] font-black uppercase tracking-widest text-blue-300 mb-2">Currency Reference</p>
          <div class="flex items-center gap-3">
            <div class="text-center bg-white/10 px-3 py-1 rounded-lg">
              <p class="text-[8px] font-black">₹85L</p>
            </div>
            <span class="text-gray-500 font-bold">→</span>
            <div class="text-center">
              <p class="text-lg font-black text-white">$101,700</p>
              <p class="text-[8px] font-bold text-blue-400 uppercase">USD Approx.</p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="mt-8 p-6 bg-blue-800/50 rounded-3xl border border-blue-700">
      <p class="text-[10px] font-bold text-blue-200 leading-relaxed italic">
        "Note: NRIs can purchase residential and commercial properties in India. 
        Agricultural land or plantation property purchases are restricted."
      </p>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import { GlobeEuropeAfricaIcon } from '@heroicons/vue/24/outline'

const props = defineProps(['lead'])
const localTime = ref('')

let timer = null

const updateTime = () => {
  if (!props.lead?.nri?.timezone) return
  localTime.value = new Date().toLocaleTimeString('en-US', {
    timeZone: props.lead.nri.timezone,
    hour: '2-digit',
    minute: '2-digit',
    hour12: true
  })
}

onMounted(() => {
  updateTime()
  timer = setInterval(updateTime, 60000)
})

onUnmounted(() => {
  if (timer) clearInterval(timer)
})
</script>
