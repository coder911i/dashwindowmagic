<template>
  <div class="h-full bg-gray-50 overflow-y-auto custom-scrollbar">
    <div class="p-8 md:p-12 max-w-7xl mx-auto w-full">
      <div class="mb-16 text-left">
        <h1 class="text-4xl font-black text-gray-900 tracking-tighter uppercase mb-2">Agency Settings</h1>
        <p class="text-gray-400 text-xs font-bold uppercase tracking-[0.2em] italic">Manage your brokerage operations</p>
      </div>

      <div class="flex flex-col lg:flex-row gap-12">
        <!-- Sidebar Tabs -->
        <div class="lg:w-72 flex flex-col gap-2 shrink-0 text-left">
          <button 
            v-for="tab in tabs" :key="tab.id"
            @click="activeTab = tab.id"
            :class="activeTab === tab.id ? 'bg-gray-900 text-white shadow-xl shadow-gray-900/10' : 'bg-white text-gray-400 hover:text-gray-900'"
            class="w-full text-left px-8 py-4 rounded-[20px] text-[10px] font-black uppercase tracking-widest transition-all flex items-center gap-4"
          >
            <component :is="tab.icon" class="w-5 h-5" />
            {{ tab.name }}
          </button>
        </div>

        <!-- Content Area -->
        <div class="flex-1">
          <div v-if="activeTab === 'profile'" class="bg-white p-12 rounded-[40px] border border-gray-100 shadow-sm text-left">
            <h3 class="text-xs font-black uppercase tracking-widest text-gray-400 mb-10 px-2">Agency Profile</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
              <div class="space-y-3">
                <label class="text-[10px] font-black text-gray-900 uppercase tracking-widest px-2">Agency Name</label>
                <input type="text" value="Premium Realty Solutions" class="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl text-xs font-bold outline-none focus:border-blue-500" />
              </div>
              <div class="space-y-3">
                <label class="text-[10px] font-black text-gray-900 uppercase tracking-widest px-2">GST Number</label>
                <input type="text" value="27AAACR1234A1Z1" class="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl text-xs font-bold outline-none focus:border-blue-500" />
              </div>
              <div class="space-y-3 md:col-span-2">
                <label class="text-[10px] font-black text-gray-900 uppercase tracking-widest px-2">Tagline</label>
                <input type="text" value="Helping you find the perfect home with AI" class="w-full px-5 py-4 bg-gray-50 border border-gray-100 rounded-2xl text-xs font-bold outline-none focus:border-blue-500" />
              </div>
            </div>
            <button class="mt-12 bg-blue-600 text-white px-12 py-5 rounded-[24px] text-xs font-black uppercase tracking-widest shadow-xl shadow-blue-500/20 hover:bg-blue-700 transition-all">
              Update Profile
            </button>
          </div>

          <div v-if="activeTab === 'subscription'" class="animate-fade-in h-full">
            <SubscriptionView />
          </div>

          <div v-if="activeTab === 'whatsapp'" class="animate-fade-in h-full">
            <WhatsAppSetupView />
          </div>

          <div v-if="activeTab === 'chatbot'" class="animate-fade-in h-full">
            <ChatbotConfigView />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import { 
  BuildingOfficeIcon, 
  UsersIcon, 
  ChatBubbleLeftRightIcon, 
  CreditCardIcon, 
  ChatBubbleBottomCenterTextIcon 
} from '@heroicons/vue/24/outline'

import SubscriptionView from './Subscription.vue'
import WhatsAppSetupView from './settings/WhatsAppSetup.vue'
import ChatbotConfigView from './ChatbotConfig.vue'

const activeTab = ref('profile')

const tabs = [
  { id: 'profile', name: 'Agency Profile', icon: BuildingOfficeIcon },
  { id: 'team', name: 'Team & Agents', icon: UsersIcon },
  { id: 'whatsapp', name: 'WhatsApp Setup', icon: ChatBubbleLeftRightIcon },
  { id: 'subscription', name: 'Subscription', icon: CreditCardIcon },
  { id: 'chatbot', name: 'Chatbot Config', icon: ChatBubbleBottomCenterTextIcon }
]
</script>

<style scoped>
.animate-fade-in {
  animation: fadeIn 0.4s ease-out;
}
@keyframes fadeIn {
  from { opacity: 0; transform: translateY(10px); }
  to { opacity: 1; transform: translateY(0); }
}
</style>
