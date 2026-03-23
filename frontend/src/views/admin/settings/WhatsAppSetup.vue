<template>
  <div class="h-full bg-gray-50 overflow-y-auto">
    <div class="p-8 md:p-12 max-w-7xl mx-auto w-full">
      <div class="mb-16 text-left">
        <h1 class="text-4xl font-black text-gray-900 tracking-tighter uppercase mb-2">WhatsApp Business API</h1>
        <p class="text-gray-400 text-xs font-bold uppercase tracking-[0.2em] italic text-left">Connect with leads where they live</p>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
        <div class="space-y-8 text-left">
          <!-- Connection Status -->
          <div class="bg-white p-10 rounded-[40px] border border-gray-100 shadow-sm">
            <div class="flex items-center justify-between mb-8 px-2">
              <h3 class="text-xs font-black uppercase tracking-widest text-gray-400">Connection Status</h3>
              <span class="px-4 py-1.5 bg-emerald-500/10 text-emerald-500 rounded-full text-[10px] font-black uppercase tracking-widest flex items-center gap-2">
                <span class="w-2 h-2 bg-emerald-500 rounded-full animate-pulse"></span>
                Connected
              </span>
            </div>
            <div class="space-y-4">
              <div class="p-6 bg-gray-50 rounded-3xl border border-gray-100">
                <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest mb-1">Business Phone ID</p>
                <p class="text-sm font-bold text-gray-900 font-mono">109283746509182</p>
              </div>
            </div>
          </div>

          <!-- Configuration Guide -->
          <div class="bg-white p-10 rounded-[40px] border border-gray-100 shadow-sm text-left">
            <h3 class="text-xs font-black uppercase tracking-widest text-gray-400 mb-8 px-2">Setup Guide</h3>
            <div class="space-y-8">
              <div v-for="(step, i) in steps" :key="i" class="flex gap-6">
                <div class="w-10 h-10 rounded-2xl bg-gray-900 text-white flex items-center justify-center text-xs font-black shrink-0">
                  {{ i + 1 }}
                </div>
                <div>
                  <h4 class="text-xs font-black text-gray-900 uppercase tracking-widest mb-1">{{ step.title }}</h4>
                  <p class="text-xs font-bold text-gray-400">{{ step.desc }}</p>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Testing Section -->
        <div class="space-y-8">
          <div class="bg-white p-10 rounded-[40px] border border-gray-100 shadow-sm text-left">
            <h3 class="text-xs font-black uppercase tracking-widest text-gray-400 mb-8 px-2">Send Test Message</h3>
            <div class="space-y-6">
              <div class="space-y-3">
                <label class="text-[10px] font-black text-gray-900 uppercase tracking-widest px-2">Phone Number</label>
                <input v-model="test.phone" type="text" placeholder="+91 9988776655" class="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl text-xs font-bold outline-none focus:border-blue-500" />
              </div>
              <div class="space-y-3">
                <label class="text-[10px] font-black text-gray-900 uppercase tracking-widest px-2">Message Content</label>
                <textarea v-model="test.message" rows="4" placeholder="Hello from Watering CRM!" class="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl text-xs font-bold outline-none focus:border-blue-500"></textarea>
              </div>
              <button @click="sendTest" class="w-full py-5 bg-blue-600 text-white rounded-[24px] text-xs font-black uppercase tracking-widest hover:bg-blue-700 shadow-xl shadow-blue-500/20 transition-all flex items-center justify-center gap-3">
                <PaperAirplaneIcon class="w-5 h-5" />
                Fire Test Message
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { reactive } from 'vue'
import { PaperAirplaneIcon } from '@heroicons/vue/24/outline'

const steps = [
  { title: 'Create App', desc: 'Go to Meta for Developers and create a Business App.' },
  { title: 'Add WhatsApp', desc: 'Add the WhatsApp product to your Meta App.' },
  { title: 'Verify Business', desc: 'Complete your Business Verification in Meta Business Manager.' },
  { title: 'Copy Tokens', desc: 'Paste your Permanent Access Token and Phone ID in Settings.' }
]

const test = reactive({
  phone: '',
  message: 'This is a test message from Watering CRM Phase 3.'
})

const sendTest = () => {
  if (!test.phone) return alert('Enter a phone number!')
  alert('Simulating message send via Meta Cloud API...')
}
</script>
