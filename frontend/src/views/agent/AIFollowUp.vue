<template>
  <div class="h-[calc(100vh-8rem)] flex overflow-hidden">
    <!-- Left: Lead Selector (35%) -->
    <div class="w-[35%] flex flex-col h-full">
      <LeadSelector 
        :leads="leads" 
        :selectedId="selectedLead?.id" 
        @select="selectLead" 
      />
    </div>

    <!-- Right: AI Composer (65%) -->
    <div class="flex-1 h-full overflow-y-auto custom-scrollbar bg-[#F9FAFB]/50">
      <div v-if="!selectedLead" class="h-full flex flex-col items-center justify-center p-12 text-center">
        <div class="w-20 h-20 bg-white rounded-3xl flex items-center justify-center mb-6 shadow-xl shadow-gray-200/50">
          <UserPlusIcon class="w-10 h-10 text-gray-300" />
        </div>
        <h2 class="text-2xl font-black text-gray-900 uppercase tracking-tighter mb-2">Ready to outreach?</h2>
        <p class="text-gray-500 max-w-xs text-sm font-medium">Select a lead from the left to start generating AI follow-ups tailored to their preferences.</p>
      </div>

      <template v-else>
        <MessageComposer 
          :lead="selectedLead" 
          :loading="isGenerating"
          @generate="handleGenerate"
        />
        <MessageHistory 
          :messages="messages" 
          :loading="isLoadingHistory"
        />
      </template>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { UserPlusIcon } from '@heroicons/vue/24/outline'
import { useLeadsStore } from '@/stores/leads'
import { useMessagesStore } from '@/stores/messages'
import { storeToRefs } from 'pinia'
import LeadSelector from '@/components/ai/LeadSelector.vue'
import MessageComposer from '@/components/ai/MessageComposer.vue'
import MessageHistory from '@/components/ai/MessageHistory.vue'

const leadsStore = useLeadsStore()
const messagesStore = useMessagesStore()

const { leads } = storeToRefs(leadsStore)
const { messages, isGenerating, isLoadingHistory } = storeToRefs(messagesStore)

const selectedLead = ref(null)

onMounted(() => {
  leadsStore.initRealtime()
})

const selectLead = (lead) => {
  selectedLead.value = lead
  messagesStore.fetchHistory(lead.id)
}

const handleGenerate = async (payload) => {
  const result = await messagesStore.generateMessage(payload)
  return result.message
}
</script>

<style scoped>
.scrollbar-hide::-webkit-scrollbar {
  display: none;
}
.scrollbar-hide {
  -ms-overflow-style: none;
  scrollbar-width: none;
}
</style>
