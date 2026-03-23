<template>
  <div class="h-full flex flex-col bg-gray-50 overflow-y-auto custom-scrollbar">
    <div class="p-8 md:p-12 max-w-7xl mx-auto w-full">
      <div class="flex justify-between items-center mb-12">
        <div class="text-left">
          <h1 class="text-4xl font-black text-gray-900 tracking-tighter uppercase mb-2">Chatbot Config</h1>
          <p class="text-gray-400 text-xs font-bold uppercase tracking-[0.2em] italic">Your 24/7 AI Sales Assistant</p>
        </div>
        <button 
          @click="saveConfig" :disabled="saving"
          class="bg-blue-600 text-white px-8 py-3 rounded-2xl text-xs font-black uppercase tracking-widest hover:bg-blue-700 shadow-xl shadow-blue-500/20 transition-all disabled:opacity-50"
        >
          {{ saving ? 'Saving...' : 'Save Configuration' }}
        </button>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
        <!-- Configuration Side -->
        <div class="space-y-8 text-left">
          <div class="bg-white p-8 rounded-[40px] border border-gray-100 shadow-sm">
            <h3 class="text-xs font-black uppercase tracking-widest text-gray-400 mb-8 px-2">Visual Branding</h3>
            <div class="space-y-6">
              <div class="space-y-3">
                <label class="text-[10px] font-black text-gray-900 uppercase tracking-widest px-2">Primary Accent Color</label>
                <div class="flex items-center gap-4">
                  <input v-model="config.primaryColor" type="color" class="w-16 h-16 rounded-2xl border-none cursor-pointer" />
                  <input v-model="config.primaryColor" type="text" class="flex-1 px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl text-xs font-bold font-mono outline-none focus:border-blue-500" />
                </div>
              </div>
            </div>
          </div>

          <div class="bg-white p-8 rounded-[40px] border border-gray-100 shadow-sm">
            <h3 class="text-xs font-black uppercase tracking-widest text-gray-400 mb-8 px-2">Messaging</h3>
            <div class="space-y-6">
              <div class="space-y-3">
                <label class="text-[10px] font-black text-gray-900 uppercase tracking-widest px-2">Greeting Message</label>
                <textarea v-model="config.greeting" rows="3" class="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl text-xs font-bold outline-none focus:border-blue-500"></textarea>
              </div>
            </div>
          </div>

          <div class="bg-white p-8 rounded-[40px] border border-gray-100 shadow-sm">
            <h3 class="text-xs font-black uppercase tracking-widest text-gray-400 mb-8 px-2">Integration Code</h3>
            <div class="relative">
              <pre class="w-full p-6 bg-gray-900 rounded-3xl text-emerald-400 text-[10px] font-mono overflow-x-auto">
&lt;script 
  src="https://api.wateringcrm.com/chatbot-widget.js" 
  data-tenant="{{ tenantId }}" 
  data-color="{{ config.primaryColor }}"&gt;
&lt;/script&gt;</pre>
              <button @click="copyCode" class="absolute top-4 right-4 p-2 bg-white/10 hover:bg-white/20 rounded-lg text-white transition-all">
                <ClipboardIcon class="w-4 h-4" />
              </button>
            </div>
          </div>
        </div>

        <!-- Preview Side -->
        <div class="relative">
          <div class="sticky top-8 flex flex-col items-center">
            <h3 class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] mb-8 italic">Live Widget Preview</h3>
            
            <!-- Mobile Frame -->
            <div class="w-[300px] h-[600px] bg-gray-900 rounded-[50px] border-[8px] border-gray-800 shadow-2xl relative overflow-hidden">
              <div class="absolute top-0 left-1/2 -translate-x-1/2 w-32 h-6 bg-gray-800 rounded-b-2xl z-20"></div>
              
              <!-- Mock Website Content -->
              <div class="p-6 pt-12 space-y-4 opacity-30 pointer-events-none">
                <div class="w-32 h-4 bg-gray-700 rounded-full"></div>
                <div class="w-full h-32 bg-gray-700 rounded-[32px]"></div>
                <div class="space-y-2">
                  <div class="w-full h-3 bg-gray-700 rounded-full"></div>
                  <div class="w-4/5 h-3 bg-gray-700 rounded-full"></div>
                </div>
              </div>

              <!-- Actual Mock Widget -->
              <div class="absolute bottom-6 right-6 flex flex-col items-end gap-4">
                <div class="w-64 bg-white rounded-3xl shadow-xl overflow-hidden animate-bounce-subtle border border-gray-100">
                  <div :style="{ background: config.primaryColor }" class="p-4 text-white text-[10px] font-black">
                    Dream Home Assistant
                  </div>
                  <div class="p-4 space-y-3 h-48 bg-gray-50">
                    <div class="bg-white p-3 rounded-2xl rounded-bl-none text-[9px] font-bold text-gray-700 border border-gray-100 max-w-[80%]">
                      {{ config.greeting }}
                    </div>
                  </div>
                </div>
                <div :style="{ background: config.primaryColor }" class="w-12 h-12 rounded-full flex items-center justify-center shadow-lg">
                  <ChatBubbleBottomCenterTextIcon class="w-6 h-6 text-white" />
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { reactive, ref } from 'vue'
import { ClipboardIcon, ChatBubbleBottomCenterTextIcon } from '@heroicons/vue/24/outline'

const tenantId = 'TENANT123' // Mock
const saving = ref(false)

const config = reactive({
  primaryColor: '#2563EB',
  greeting: 'Hi! How can I help you find your dream home today?',
  nameRequired: true,
  phoneRequired: true
})

const saveConfig = async () => {
  saving.value = true
  // API Call to save
  setTimeout(() => saving.value = false, 1000)
}

const copyCode = () => {
  const code = `<script src="https://api.wateringcrm.com/chatbot-widget.js" data-tenant="${tenantId}" data-color="${config.primaryColor}"><\/script>`
  navigator.clipboard.writeText(code)
  alert('Embed code copied!')
}
</script>

<style scoped>
@keyframes bounce-subtle {
  0%, 100% { transform: translateY(0); }
  50% { transform: translateY(-5px); }
}
.animate-bounce-subtle {
  animation: bounce-subtle 3s infinite ease-in-out;
}
</style>
