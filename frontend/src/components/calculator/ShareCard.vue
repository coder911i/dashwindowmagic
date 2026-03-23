<template>
  <div class="mt-8 p-6 bg-gray-900 rounded-3xl border border-white/10">
    <div class="flex items-center justify-between mb-6">
      <div class="flex items-center gap-3">
        <div class="w-10 h-10 rounded-xl bg-green-500/10 flex items-center justify-center">
          <ShareIcon class="w-5 h-5 text-green-500" />
        </div>
        <h3 class="text-xs font-black text-white uppercase tracking-widest">Share with Lead</h3>
      </div>
    </div>

    <div class="p-4 bg-white/5 rounded-2xl border border-white/5 mb-6">
      <p class="text-xs text-gray-400 font-medium leading-relaxed italic whitespace-pre-wrap">
        {{ message }}
      </p>
    </div>

    <div class="flex gap-4">
      <button @click="copy" class="flex-1 py-3 border border-white/10 rounded-xl text-[10px] font-black uppercase tracking-widest text-gray-400 hover:bg-white/5 transition-all">
        Copy Draft
      </button>
      <button @click="share" class="flex-1 py-3 bg-[#25D366] text-white rounded-xl text-[10px] font-black uppercase tracking-widest flex items-center justify-center gap-2 hover:bg-[#20bd5a] transition-all">
        WhatsApp
      </button>
    </div>
  </div>
</template>

<script setup>
import { ShareIcon } from '@heroicons/vue/24/outline'

const props = defineProps({
  message: String
})

const copy = () => {
  navigator.clipboard.writeText(props.message)
  alert('Draft copied!')
}

const share = () => {
  window.open(`https://wa.me/?text=${encodeURIComponent(props.message)}`, '_blank')
}
</script>
